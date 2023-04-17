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
        Schema::create('ATT_candidate_contact_people', function (Blueprint $table) {
            $table->bigIncrements('ATT_candidate_contact_people_id');

            $table->string('name');
            $table->string('first_last_name');
            $table->string('second_last_name')->nullable();
            $table->boolean('is_national')->default(true);

            $table->string('lives_in')->nullable();
            $table->string('status')->default('active');

            $table->unsignedBigInteger('ATT_candidate_id');
            $table->foreign('ATT_candidate_id')->references('ATT_candidate_id')->on('ATT_candidates');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ATT_candidate_contact_people');
    }
};
