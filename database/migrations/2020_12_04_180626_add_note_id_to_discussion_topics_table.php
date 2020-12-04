<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteIdToDiscussionTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discussion_topics', function (Blueprint $table) {
            $table->bigInteger('note_id')->unsigned();
            $table->foreign('note_id')->references('id')->on('meeting_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discussion_topics', function (Blueprint $table) {
            //
        });
    }
}
