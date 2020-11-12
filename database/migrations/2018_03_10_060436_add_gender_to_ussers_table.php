<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGenderToUssersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            \DB::statement("CREATE DOMAIN gender CHAR(1) CHECK (value IN ('M', 'F', 'O', 'B'));");
            \DB::statement("ALTER TABLE users ADD COLUMN gender gender;");
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
            \DB::statement("ALTER TABLE users DROP COLUMN gender;");
            \DB::statement("DROP DOMAIN gender;");
        });
    }
}
