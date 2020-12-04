<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteColumnsInMeetingNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE meeting_notes DROP COLUMN content');
        DB::statement('ALTER TABLE meeting_notes DROP COLUMN decision');
        DB::statement('ALTER TABLE meeting_notes DROP COLUMN deadline_date');
        DB::statement('ALTER TABLE meeting_notes DROP COLUMN person_in_charge');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meeting_notes', function (Blueprint $table) {
            //
        });
    }
}
