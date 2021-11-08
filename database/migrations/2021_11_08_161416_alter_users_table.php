<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('nik')->after('role')->nullable();
            $table->string('birth_place')->after('nik')->nullable();
            $table->string('birth_date')->after('birth_place')->nullable();
            $table->enum('sex', ['Laki-Laki', 'Perempuan'])->after('birth_date')->nullable();
            $table->string('religion')->after('sex')->nullable();
            $table->string('class')->after('religion')->nullable();
            $table->unsignedBigInteger('ijazah_number')->after('class')->nullable();
            $table->string('ijazah_date')->after('ijazah_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nik');
            $table->dropColumn('birth_place');
            $table->dropColumn('birth_date');
            $table->dropColumn('sex');
            $table->dropColumn('religion');
            $table->dropColumn('class');
            $table->dropColumn('ijazah_number');
            $table->dropColumn('ijazah_date');
        });
    }
}
