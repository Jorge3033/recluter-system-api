<?php

use App\Http\Controllers\Api\v1\Document\DocumentController;
use App\Http\Controllers\Api\v1\Document\DocumentJobController;
use Illuminate\Support\Facades\Route;


Route::get('/', [DocumentController::class, 'index'])
    ->name('document.index');
Route::post('/', [DocumentController::class, 'store'])
    ->name('document.store');
Route::get('/{ATT_document_id}', [DocumentController::class, 'show'])
    ->name('document.show');
Route::post('/{ATT_document_id}', [DocumentController::class, 'update'])
    ->name('document.update');
Route::delete('/{ATT_document_id}', [DocumentController::class, 'destroy'])
    ->name('document.destroy');

Route::put(
    '/{ATT_document_id}/activate_ocr',
    [DocumentController::class, 'activateOcr']
)
    ->name('document.activateOcr');

Route::get(
    '/{ATT_document_id}/get_file',
    [DocumentController::class, 'showDocument']
)
    ->name('document.getFile');


/* ############################## */
/* ########## Documents FROM Job  ######## */


Route::get(
    '/{ATT_document_id}/document-jobs',
    [DocumentJobController::class, 'index']
)
    ->name('documentJob.index');

// The parameter ID is the ID of the job

Route::get(
    '/{ID}/jobs-documents',
    [DocumentJobController::class, 'getDocumentFromJobs']
)
    ->name('documentJob.index');

Route::post(
    '/{ATT_document_id}/document-jobs/{ID}',
    [DocumentJobController::class, 'store']
)
    ->name('documentJob.store');

Route::get(
    '/{ATT_document_jobs_id}/show-document-job',
    [DocumentJobController::class, 'show']
)
    ->name('documentJob.show');

Route::put(
    '/{ATT_document_id}/update-document-job/{ATT_document_jobs_id}/',
    [DocumentJobController::class, 'update']
)
    ->name('documentJob.update');

Route::put(
    '/{ID}/update-job-document/{ATT_document_jobs_id}',
    [DocumentJobController::class, 'updateJobDocument']
)
    ->name('documentJob.update');
Route::delete(
    '/{ATT_document_jobs_id}/delete-document-job',
    [DocumentJobController::class, 'destroy']
)
    ->name('documentJob.destroy');
