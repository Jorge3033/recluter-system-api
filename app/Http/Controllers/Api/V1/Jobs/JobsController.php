<?php

namespace App\Http\Controllers\Api\V1\Jobs;

use App\Http\Controllers\Controller;
use App\Models\SIACH\SIACH_ATT_Vacantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function getJobDocumentsList(SIACH_ATT_Vacantes $ID)
    {

        $query = "SELECT

                ATT_documents.ATT_document_id as document_id,
                ATT_documents.name as document_name,

                CASE WHEN ATT_documents.has_ocr = 1 THEN 'true' ELSE 'false' END as document_has_ocr,
                CASE WHEN ATT_documents.ocr_is_active = 1 THEN 'true' ELSE 'false' END as document_ocr_is_active,

                ATT_document_jobs.ATT_document_jobs_id as document_job_id,
                ATT_document_jobs.importance as document_job_importance,

                CASE WHEN ATT_document_jobs.is_national = 1 THEN 'true' ELSE 'false' END as document_job_is_national

            FROM ATT_document_jobs
            INNER JOIN ATT_documents ON ATT_document_jobs.ATT_document_id = ATT_documents.ATT_document_id
            WHERE ATT_Vacantes_id = $ID->ID
         ";

        $result = DB::select($query);

        return  response()->json([
            'job_info' => $ID,
            'documents' => $result
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function getJobsByJobType(Request $request)
    {

        //Validate the query parameters
        $request->validate([
            'jobType' => [
                'required', 'string',
                'regex:/^(operativo|confianza)$/i'
            ]
        ], [
            'jobType.regex' => 'El tipo de vacante debe ser "operativo" o "confianza"'
        ]);

        $query = "SELECT
            ID as job_id,
            Titulo as job_title,
            Codigo as job_code
        FROM ATT_Vacantes WHERE FlujoVacante = '$request->jobType' ";

        $result = DB::select($query);

        return response()->json($result);
    }
}
