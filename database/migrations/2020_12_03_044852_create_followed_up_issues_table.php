<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowedUpIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followed_up_issues', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->date('date');
            $table->bigInteger('issue_id')->unsigned()->unique();
            $table->foreign('issue_id')->references('id')->on('issues');
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
        Schema::dropIfExists('followed_up_issues');
    }
}
