<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_topics', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('content');
            $table->text('decision');
            $table->date('deadline_date');
            $table->string('person_in_charge');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('departement_id')->unsigned();

            $table->foreign('departement_id')->references('id')->on('departements');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('discussion_topics');
    }
}
