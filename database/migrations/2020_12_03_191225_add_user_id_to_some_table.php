<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUserIdToSomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_names = ['meeting_actions', 'followed_up_issues', 'discussion_actions'];

        for ($i=0; $i < sizeof($table_names); $i++) {
            $table_name = $table_names[$i];
            $add_user_id = 'ALTER TABLE '.$table_name.' ADD user_id BIGINT UNSIGNED NULL';
            $set_user_id_fk = 'ALTER TABLE '.$table_name.' ADD CONSTRAINT '.$table_name.'_users_fk FOREIGN KEY (user_id) REFERENCES users(id)';
            DB::statement($add_user_id);
            DB::statement($set_user_id_fk);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
