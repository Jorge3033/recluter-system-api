<?php

namespace App\Http\Controllers\Api\v1\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Candidate\CreateCandidateRequest;
use App\Http\Requests\Api\V1\Candidate\UpdateCandidateRequest;
use App\Http\Resources\Api\V1\Candidate\CandidateCollection;
use App\Http\Resources\Api\V1\Candidate\CandidateResource;
use App\Models\SIACH\SIACH_AT_Candidate;
use Illuminate\Http\Request;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\DB;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new CandidateCollection(SIACH_AT_Candidate::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCandidateRequest $request)
    {
        $candidate = SIACH_AT_Candidate::create($request->validated());
        return new CandidateResource($candidate);
    }

    /**
     * Display the specified resource.
     */
    public function show(SIACH_AT_Candidate $ATT_candidate_id)
    {
        return new CandidateResource($ATT_candidate_id);
    }


    public function getDocumentList(SIACH_AT_Candidate $ATT_candidate_id)
    {

        $query = "SELECT
                ATT_Vacantes.Titulo as job_title,
                ATT_Vacantes.Codigo as job_code,

                ATT_documents.ATT_document_id as document_id,
                ATT_documents.name as document_name,
                ATT_documents.has_ocr as document_has_ocr,
                ATT_documents.ocr_is_active as document_ocr_is_active,

                ATT_document_jobs.ATT_document_jobs_id as document_job_id,
                ATT_document_jobs.importance as document_job_importance,
                ATT_document_jobs.is_national as document_job_is_national,

                CASE
                    WHEN EXISTS
                        ( SELECT ATT_candidate_document_id FROM ATT_candidate_documents
                            WHERE ATT_candidate_id = '$ATT_candidate_id->ATT_candidate_id'
                            AND ATT_document_id = ATT_documents.ATT_document_id
                        )
                        THEN 'true'
                        ELSE 'false'
                    END
                AS has_loaded

            FROM ATT_document_jobs
            INNER JOIN ATT_Vacantes  ON ATT_document_jobs.ATT_Vacantes_id = ATT_Vacantes.ID
            INNER JOIN ATT_documents ON ATT_document_jobs.ATT_document_id = ATT_documents.ATT_document_id
            WHERE ATT_Vacantes_id = $ATT_candidate_id->ATT_Vacantes_id
         ";


        $result = DB::select($query);

        $jobs = DB::table('ATT_Vacantes')
            ->select('Titulo', 'Codigo')
            ->where('ID', $ATT_candidate_id->ATT_Vacantes_id)
            ->get();

        return  response()->json([
            'candudate' => $ATT_candidate_id,
            'document_list' => $result,
            'job' => $jobs[0] ?? null
        ], 200);
    }


    public function getQrCodeInfo(SIACH_AT_Candidate $ATT_candidate_id)
    {

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);
        $writer->writeFile($ATT_candidate_id->candidate_key, 'qrcode.png');
        if (request()->query('download') == 'true') return response()->download('qrcode.png');
        return response()->file('qrcode.png');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCandidateRequest $request, SIACH_AT_Candidate $ATT_candidate_id)
    {
        $ATT_candidate_id->update($request->validated());
        return new CandidateResource($ATT_candidate_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SIACH_AT_Candidate $ATT_candidate_id)
    {
        $ATT_candidate_id->delete();
        return response()->json(null, 204);
    }
}
