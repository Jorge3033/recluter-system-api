<?php

namespace App\Http\Controllers\Api\v1\CandidateDocuments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CandidateDocuments\CreateCandidateDocumentRequest;
use App\Http\Requests\Api\V1\CandidateDocuments\UpdateCandidateDocumentRequest;
use App\Http\Resources\Api\V1\Candidate\CandidateDocumentsResource;
use App\Http\Resources\Api\V1\Document\DocumentResource;
use App\Models\SIACH\SIACH_AT_Candidate;
use App\Models\SIACH\SIACH_AT_CandidateDocuments;
use App\Models\SIACH\SIACH_AT_Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Return_;

class CandidateDocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private const FILE_SYSTEM = 's3';
    private const ROOT_FOLDER = 'CandidateDocuments';

    public function index(SIACH_AT_Candidate $ATT_candidate_id)
    {
        $documents = DB::select(
            'SELECT * FROM ATT_candidate_documents WHERE ATT_candidate_id = ?',
            [$ATT_candidate_id->ATT_candidate_id]
        );
        return response()->json($documents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        SIACH_AT_Candidate $ATT_candidate_id,
        CreateCandidateDocumentRequest $request,
        SIACH_AT_Document $ATT_document_id
    ) {
        //TODO Implementar el metodo para colocar los campos faltantes al momento de insertar el documento
        try {
            $filePath = '';
            return DB::transaction(function () use ($request, $ATT_candidate_id, &$filePath, $ATT_document_id) {

                //UploadFile to s3
                $documentFolder = self::ROOT_FOLDER . '/' . $ATT_candidate_id->candidate_key;
                $path = $request->file('document')->store($documentFolder, self::FILE_SYSTEM);

                $filePath = $path;

                $document = SIACH_AT_CandidateDocuments::create([
                    'name' => $ATT_document_id->name,
                    'path' => $path,
                    'file_system_location' => self::FILE_SYSTEM,
                    'ATT_candidate_id' => $ATT_candidate_id->ATT_candidate_id,
                    'ATT_document_id' => $ATT_document_id->ATT_document_id,
                    'document_type' => 'colocarlo de la tabla job documens',
                    'document_is_national' => true,
                    'document_importance' => 'colocarlo de la tabla job documens',
                    'ocr_strategy' => $ATT_document_id->ocr_strategy,
                    'ocr_api_id' => $request->ocr_api_id ?? null,
                    'status' => 'pending_review',
                ]);

                return new DocumentResource($document);
            });
        } catch (\Exception $e) {

            //Delete file from s3
            if ($filePath) {
                Storage::disk(self::FILE_SYSTEM)->delete($filePath);
            }

            return response()->json([
                'message' => 'Error al guardar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(
        SIACH_AT_Candidate $ATT_candidate_id,
        SIACH_AT_Document $ATT_document_id
    ) {
        $candidateDocument = SIACH_AT_CandidateDocuments::where(
            'ATT_candidate_id',
            $ATT_candidate_id->ATT_candidate_id
        )->where('ATT_document_id', $ATT_document_id->ATT_document_id)
            ->first();

        if (!$candidateDocument) {
            return response()->json([
                'message' => 'No se encontro el documento'
            ], 404);
        }

        return new DocumentResource($candidateDocument);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        SIACH_AT_Candidate $ATT_candidate_id,
        UpdateCandidateDocumentRequest $request,
        SIACH_AT_Document $ATT_document_id
    ) {
        try {
            return DB::transaction(function () use ($request, $ATT_candidate_id, $ATT_document_id) {

                $candidateDocument = SIACH_AT_CandidateDocuments::where(
                    'ATT_candidate_id',
                    $ATT_candidate_id->ATT_candidate_id
                )->where('ATT_document_id', $ATT_document_id->ATT_document_id)
                    ->first();

                if (!$candidateDocument) {
                    return response()->json([
                        'message' => 'No se encontro el documento'
                    ], 404);
                }

                $candidateDocument->update($request->validated());

                //OverWrite file to s3 if exists
                if ($request->file('document')) {
                    $documentFolder = self::ROOT_FOLDER . '/' . $ATT_candidate_id->candidate_key;
                    $path = $request->file('document')->store($documentFolder, self::FILE_SYSTEM);
                    $candidateDocument->path = $path;
                    $candidateDocument->file_system_location = self::FILE_SYSTEM;
                }

                $candidateDocument->save();

                return new DocumentResource($candidateDocument);
            });
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error al actualizar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        SIACH_AT_Candidate $ATT_candidate_id,
        SIACH_AT_Document $ATT_document_id
    ) {

        $candidateDocument = SIACH_AT_CandidateDocuments::where(
            'ATT_candidate_id',
            $ATT_candidate_id->ATT_candidate_id
        )->where('ATT_document_id', $ATT_document_id->ATT_document_id)
            ->first();

        if (!$candidateDocument) {
            return response()->json([
                'message' => 'No se encontro el documento'
            ], 404);
        }

        $candidateDocument->delete();
        return new CandidateDocumentsResource($candidateDocument);
    }


    public function download(SIACH_AT_Candidate $ATT_candidate_id, SIACH_AT_Document $ATT_document_id)
    {

        // if query string is present, then download file
        $query = request()->query();

        $candidateDocument = SIACH_AT_CandidateDocuments::where(
            'ATT_candidate_id',
            $ATT_candidate_id->ATT_candidate_id
        )->where('ATT_document_id', $ATT_document_id->ATT_document_id)
            ->first();


        if (!$candidateDocument) {
            return response()->json([
                'message' => 'No se encontro el documento'
            ], 404);
        }

        $path = $candidateDocument->path;



        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $file = Storage::disk($candidateDocument->file_system_location)->get($path);

        $mime_type = Storage::disk($candidateDocument->file_system_location)
            ->mimeType($path);

        if (isset($query['download'])) {
            return response($file, 200)
                ->header(
                    'Content-Disposition',
                    'attachment; filename="' . $candidateDocument->name . "." . $extension . '"'
                );
        }

        return response($file, 200)
            ->header('Content-Type', $mime_type)
            ->header(
                'Content-Disposition',
                'inline; filename="' . $candidateDocument->name . "." . $extension . '"'
            );
    }

    public function downloadAll(SIACH_AT_Candidate $ATT_candidate_id)
    {
        $documents = DB::select(
            'SELECT * FROM ATT_candidate_documents WHERE ATT_candidate_id = ?',
            [$ATT_candidate_id->ATT_candidate_id]
        );

        $zip = new \ZipArchive();
        $zipName = "$ATT_candidate_id->candidate_key-$ATT_candidate_id->name-documentacion.zip";
        $zip->open($zipName, \ZipArchive::CREATE);

        foreach ($documents as $document) {

            $path = $document->path;
            $file_name = $document->name . '.' . pathinfo($path, PATHINFO_EXTENSION);

            $file = Storage::disk($document->file_system_location)->get($path);

            $zip->addFromString($file_name, $file);
        }

        $zip->close();

        return response()->download($zipName);
    }
}
