<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('content');
            $table->string('place');
            $table->string('email');
            $table->string('issuer');
            $table->string('issue_input');
            $table->bigInteger('departement_id')->unsigned();

            $table->foreign('departement_id')->references('id')->on('departements');

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
        Schema::dropIfExists('issues');
    }
}
