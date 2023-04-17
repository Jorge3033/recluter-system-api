<?php

namespace App\Http\Controllers\Api\v1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\CreateDocumentRequest;
use App\Http\Requests\Api\V1\Document\UserUpdateDocumentRequest;
use App\Http\Resources\Api\V1\Document\DocumentCollection;
use App\Http\Resources\Api\V1\Document\DocumentResource;
use App\Models\SIACH\SIACH_AT_Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{

    private const FILE_SYSTEM = 'local';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new DocumentCollection(SIACH_AT_Document::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDocumentRequest $request)
    {
        try {
            $file_path = '';
            return DB::transaction(function () use ($request, &$file_path) {
                $path = $request->file('document')->store('documents', self::FILE_SYSTEM);

                $file_path = $path;

                $document = SIACH_AT_Document::create([
                    'name' => $request->input('name'),
                    'path' => $path,
                    'mime_type' => $request->file('document')->getMimeType(),
                    'file_system_location' => self::FILE_SYSTEM,
                    'has_ocr' => filter_var($request->input('has_ocr'), FILTER_VALIDATE_BOOLEAN),
                    'ocr_strategy' => $request->input('ocr_strategy'),
                    'document_strategy' => $request->input('document_strategy'),
                ]);

                return new DocumentResource($document);
            });
        } catch (\Exception $e) {
            // Delete the file if the transaction fails from $file_path
            Storage::disk(self::FILE_SYSTEM)->delete($file_path);


            return response()->json([
                'message' => 'Error al crear el documento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SIACH_AT_Document $ATT_document_id)
    {
        return new DocumentResource($ATT_document_id);
    }

    public function showDocument(SIACH_AT_Document $ATT_document_id)
    {
        $file = Storage::disk($ATT_document_id->file_system_location)->get($ATT_document_id->path);

        $split = explode('.', $ATT_document_id->path);

        //Retuirn the file ans set file name using the name of the document with extension
        return response($file, 200)
            ->header('Content-Type', $ATT_document_id->mime_type)
            ->header('Content-Disposition', 'inline; filename="' . $ATT_document_id->name . '.' . end($split) . '"');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateDocumentRequest $request, SIACH_AT_Document $ATT_document_id)
    {
        try {
            return DB::transaction(function () use ($request, $ATT_document_id) {
                $ATT_document_id->update($request->validated());

                if ($request->has('document')) {

                    // movo the old file to the documentTrash folder

                    $date = date('Y-m-d H:i:s');
                    //Replace Document Name spaces with underscore

                    //GetExtension of the file
                    $split = explode('.', $ATT_document_id->path);
                    $extension = end($split);
                    $documentName = $date . '.' . $extension;

                    $documentFolder = str_replace(' ', '_', $ATT_document_id->name);

                    Storage::disk($ATT_document_id->file_system_location)
                        ->move($ATT_document_id->path, 'documentTrash/' . $documentFolder . '/' . $documentName);

                    $path = $request->file('document')->store('documents', self::FILE_SYSTEM);
                    $ATT_document_id->path = $path;
                    $ATT_document_id->mime_type = $request->file('document')->getMimeType();
                    $ATT_document_id->file_system_location = self::FILE_SYSTEM;
                    $ATT_document_id->save();
                }



                return new DocumentResource($ATT_document_id);
            });

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Error al actualizar el documento',
                'error' => $th->getMessage(),
            ], 500);

        }

    }


    public function activateOcr(SIACH_AT_Document $ATT_document_id)
    {
        $ATT_document_id->has_ocr = true;
        $ATT_document_id->save();

        return new DocumentResource($ATT_document_id);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SIACH_AT_Document $ATT_document_id)
    {
        $ATT_document_id->delete();
        return response()->json([
            'message' => 'Documento eliminado correctamente',
        ], 200);
    }
}
