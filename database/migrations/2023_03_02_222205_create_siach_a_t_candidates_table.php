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
        Schema::create('ATT_candidates', function (Blueprint $table) {
            $table->bigIncrements('ATT_candidate_id');
            $table->string('candidate_key')->unique();
            $table->string('name');
            $table->string('first_last_name');
            $table->string('second_last_name')->nullable();
            $table->boolean('is_national')->default(true);

            //Direccion Data
            $table->string('street')->nullable();
            $table->string('number_in')->nullable();
            $table->string('number_out')->nullable();
            $table->string('colony')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Mexico');
            $table->string('postal_code')->nullable();

            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('curp')->nullable();

            $table->date('birth_date');

            $table->unsignedInteger('ATT_Vacantes_id');
            $table->foreign('ATT_Vacantes_id')->references('ID')->on('ATT_Vacantes');

            $table->string('status')->default('in_process');

            //
            $table->string('come_from')->default('internal')
                ->comment('internal, web, whatsapp, etc');

            $table->boolean('is_in_syndicated')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ATT_candidates');
    }
};
