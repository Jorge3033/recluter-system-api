<?php

use App\Http\Controllers\Api\v1\Candidate\CandidateController;
use App\Http\Controllers\Api\v1\CandidateDocuments\CandidateDocumentsController;
use Illuminate\Support\Facades\Route;


Route::get(
    '/',
    [CandidateController::class , 'index']
);
Route::post(
    '/',
    [CandidateController::class, 'store']
);

Route::get(
    '/{ATT_candidate_id}',
    [CandidateController::class, 'show']
);

Route::get(
    '/{ATT_candidate_id}/get-document-list',
    [CandidateController::class, 'getDocumentList']
);


Route::put(
    '/{ATT_candidate_id}',
    [CandidateController::class, 'update']
);
Route::delete(
    '/{ATT_candidate_id}',
    [CandidateController::class, 'destroy']
);

Route::get(
    '/{ATT_candidate_id}/qrcode',
    [CandidateController::class, 'getQrCodeInfo']
);

/*¨############################## */
/*¨Candidate Documents Routes */

Route::get(
    '/{ATT_candidate_id}/documents',
    [CandidateDocumentsController::class, 'index']
)->name('candidate.documents.index');
Route::get(
    '/{ATT_candidate_id}/document/{ATT_document_id}',
    [CandidateDocumentsController::class, 'show']
)->name('candidate.documents.show');

Route::get(
    '/{ATT_candidate_id}/download-all-documents',
    [CandidateDocumentsController::class, 'downloadAll']
)->name('candidate.documents.downloadAll');

Route::get(
    '/{ATT_candidate_id}/download/{ATT_document_id}',
    [CandidateDocumentsController::class, 'download']
)->name('candidate.documents.download');

Route::post(
    '{ATT_candidate_id}/add-document/{ATT_document_id}',
    [CandidateDocumentsController::class, 'store']
)->name('candidate.documents.store');

Route::post(
    '{ATT_candidate_id}/set-document/{ATT_document_id}',
    [CandidateDocumentsController::class, 'update']
)->name('candidate.documents.update');
Route::delete(
    '{ATT_candidate_id}/remove-document/{ATT_document_id}',
    [CandidateDocumentsController::class, 'destroy']
)->name('candidate.documents.destroy');
