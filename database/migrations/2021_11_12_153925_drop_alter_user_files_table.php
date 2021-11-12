<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAlterUserFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_files', function (Blueprint $table) {
            $table->dropColumn('is_checked');
            $table->dropColumn('is_notified');
            $table->dropColumn('is_verified');
            $table->dropColumn('is_locked');
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
            $table->boolean('is_checked')->default(false);
            $table->boolean('is_notified')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_locked')->after('content_type')->default(true);
        });
    }
}
