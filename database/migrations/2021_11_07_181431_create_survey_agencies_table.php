<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_agencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('first_agency_id'); // foreign
            $table->foreign('first_agency_id')->references('id')->on('agencies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('second_agency_id'); // foreign
            $table->foreign('second_agency_id')->references('id')->on('agencies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('third_agency_id'); // foreign
            $table->foreign('third_agency_id')->references('id')->on('agencies')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_agencies');
    }
}
