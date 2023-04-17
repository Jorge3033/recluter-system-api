<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ATT_candidate_documents', function (Blueprint $table) {
            $table->bigIncrements('ATT_candidate_document_id');

            $table->string('name');
            $table->string('path');
            $table->string('file_system_location');

            $table->unsignedBigInteger('ATT_candidate_id');
            $table->foreign('ATT_candidate_id')->references('ATT_candidate_id')->on('ATT_candidates');

            $table->unsignedBigInteger('ATT_document_id');
            $table->foreign('ATT_document_id')->references('ATT_document_id')->on('ATT_documents');
            $table->string('document_type');
            $table->string('document_is_national');
            $table->string('document_importance');

            $table->string('ocr_strategy');

            $table->string('ocr_api_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ATT_candidate_documents');
    }
};
