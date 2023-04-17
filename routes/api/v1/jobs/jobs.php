<?php

use App\Http\Controllers\Api\V1\Jobs\JobsController;
use Illuminate\Support\Facades\Route;


Route::get(
    '/get-jobs-by-job-type',
    [JobsController::class, 'getJobsByJobType']
)->name('jobs.getJobsByJobType');

Route::get(
    '/{ID}/get-job-documents-list',
    [JobsController::class, 'getJobDocumentsList']
)->name('jobs.getJobDocumentsList');
