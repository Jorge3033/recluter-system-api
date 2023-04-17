<?php

namespace App\Http\Controllers\Api\v1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\DocumentJob\CreateDocumentJobRequest;
use App\Http\Requests\Api\V1\DocumentJob\UpdateDocumentJobDataRequest;
use App\Http\Requests\Api\V1\DocumentJob\UptadeDocumentJobRequest;
use App\Http\Resources\Api\V1\DocumentJob\DocumentJobCollection;
use App\Http\Resources\Api\V1\DocumentJob\DocumentJobResource;
use App\Models\SIACH\SIACH_AT_Document;
use App\Models\SIACH\SIACH_AT_DocumentJob;
use App\Models\SIACH\SIACH_ATT_Vacantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SIACH_AT_Document $ATT_document_id)
    {
        $query = DB::table('ATT_document_jobs')
            ->join('ATT_Vacantes', 'ATT_Vacantes.ID', 'ATT_document_jobs.ATT_Vacantes_id')
            ->where('ATT_document_jobs.ATT_document_id', $ATT_document_id->ATT_document_id)
            ->whereNull('ATT_document_jobs.deleted_at')
            ->select([
                'ATT_document_jobs.ATT_document_jobs_id',
                'ATT_document_jobs.ATT_document_id',
                'ATT_document_jobs.ATT_Vacantes_id',
                'ATT_document_jobs.is_national',
                'ATT_document_jobs.importance',
                'ATT_Vacantes.Codigo',
                'ATT_Vacantes.Titulo',
            ])->get();

        return response()->json([
            'data' => $query
        ]);
        ;
    }

    public function getDocumentFromJobs(SIACH_ATT_Vacantes $ID)
    {
        $query = DB::table('ATT_document_jobs')
            ->join('ATT_documents', 'ATT_documents.ATT_document_id', 'ATT_document_jobs.ATT_document_id')
            ->where('ATT_document_jobs.ATT_Vacantes_id', $ID->ID)
            ->whereNull('ATT_document_jobs.deleted_at')
            ->select([
                'ATT_document_jobs.ATT_document_jobs_id',
                'ATT_document_jobs.ATT_document_id',
                'ATT_document_jobs.ATT_Vacantes_id',
                'ATT_document_jobs.is_national',
                'ATT_document_jobs.importance',
                'ATT_documents.name as document_name',
                'ATT_documents.has_ocr as document_has_ocr',
            ])->get()
            ->each(function ($item) use ($ID) {
                $item->vacante = [
                    'ID' => $ID->ID,
                    'Codigo' => $ID->Codigo,
                    'Titulo' => $ID->Titulo,
                ];
                return $item;
            });

        return response()->json([
            'data' => $query
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SIACH_AT_Document $ATT_document_id, CreateDocumentJobRequest $request, SIACH_ATT_Vacantes $ID)
    {
        $data = SIACH_AT_DocumentJob::create([
            'ATT_document_id' => $ATT_document_id->ATT_document_id,
            'ATT_Vacantes_id' => $ID->ID,
            'is_national' => $request->is_national,
            'importance' => $request->importance,
        ]);
        return new DocumentJobResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(SIACH_AT_DocumentJob $ATT_document_jobs_id)
    {
        $query = DB::table('ATT_document_jobs')
            ->join('ATT_documents', 'ATT_documents.ATT_document_id', 'ATT_document_jobs.ATT_document_id')
            ->join('ATT_Vacantes', 'ATT_Vacantes.ID', 'ATT_document_jobs.ATT_Vacantes_id')
            ->where('ATT_document_jobs.ATT_document_jobs_id', $ATT_document_jobs_id->ATT_document_jobs_id)
            ->whereNull('ATT_document_jobs.deleted_at')
            ->select([
                'ATT_document_jobs.ATT_document_jobs_id',
                'ATT_document_jobs.ATT_document_id',
                'ATT_document_jobs.ATT_Vacantes_id',
                'ATT_document_jobs.is_national',
                'ATT_document_jobs.importance',
                'ATT_documents.name as document_name',
                'ATT_documents.has_ocr as document_has_ocr',
                'ATT_Vacantes.Codigo as job_code',
                'ATT_Vacantes.Titulo as job_title',
            ])->get();

        return response()->json([
            'data' => $query
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        SIACH_AT_Document $ATT_document_id,
        UpdateDocumentJobDataRequest $request,
        SIACH_AT_DocumentJob $ATT_document_jobs_id,
    ) {

        $ATT_document_jobs_id->update([
            'ATT_document_id' => $ATT_document_id->ATT_document_id,
            'is_national' => $request->is_national,
            'importance' => $request->importance,
        ]);
        return new DocumentJobResource($ATT_document_jobs_id);
    }

    public function updateJobDocument(
        SIACH_ATT_Vacantes $ID,
        UpdateDocumentJobDataRequest $request,
        SIACH_AT_DocumentJob $ATT_document_jobs_id,
    ) {
        $ATT_document_jobs_id->update([
            'ATT_Vacantes_id' => $ID->ID,
            'is_national' => $request->is_national,
            'importance' => $request->importance,
        ]);
        return new DocumentJobResource($ATT_document_jobs_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SIACH_AT_DocumentJob $ATT_document_jobs_id)
    {
        $ATT_document_jobs_id->delete();
        return response()->json(null, 204);
    }
}
