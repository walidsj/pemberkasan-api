<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_files', function (Blueprint $table) {
            $table->timestamp('locked_at')->after('is_locked')->nullable();
            $table->timestamp('checked_at')->after('locked_at')->nullable();
            $table->timestamp('notified_at')->after('checked_at')->nullable();
            $table->timestamp('verified_at')->after('notified_at')->nullable();
            $table->timestamp('backupped_at')->after('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_files', function (Blueprint $table) {
            $table->dropColumn('locked_at');
            $table->dropColumn('checked_at');
            $table->dropColumn('notified_at');
            $table->dropColumn('verified_at');
            $table->dropColumn('backupped_at');
        });
    }
}
