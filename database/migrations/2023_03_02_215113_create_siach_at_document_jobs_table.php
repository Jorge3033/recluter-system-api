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
        Schema::create('ATT_document_jobs', function (Blueprint $table) {
            $table->bigIncrements('ATT_document_jobs_id');

            $table->unsignedBigInteger('ATT_document_id');
            $table->foreign('ATT_document_id')
                ->references('ATT_document_id')
                ->on('ATT_documents');

            $table->unsignedInteger('ATT_Vacantes_id');
            $table->foreign('ATT_Vacantes_id')
                ->references('ID')
                ->on('ATT_Vacantes');

            $table->boolean('is_national')->default(true);
            $table->string('importance')->default('low');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ATT_document_jobs');
    }
};
