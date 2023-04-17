<?php
use Illuminate\Support\Facades\Route;

Route::prefix('/documents')->group(function () {
    include(__DIR__ . '/documents/documents.php');
});

Route::prefix('/candidates')->group(function () {
    include(__DIR__ . '/candidates/candidate.php');
});


Route::prefix('/jobs')->group(function () {
    include(__DIR__ . '/jobs/jobs.php');
});
