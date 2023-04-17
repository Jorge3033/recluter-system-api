<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ATT_documents', function (Blueprint $table) {
            $table->bigIncrements('ATT_document_id');
            $table->string('name')->unique();
            $table->string('path')->unique();
            $table->string('mime_type')->nullable();
            $table->string('file_system_location');

            $table->boolean('has_ocr')->default(false);
            $table->string('ocr_strategy')->nullable();

            $table->boolean('ocr_is_active')->default(false)
                ->comment('Indicates if the OCR is active for this document');
            $table->string('ocr_is_active_justification')->nullable()
                ->comment('Justification for the OCR activation/deactivation');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ATT_documents');
    }
};
