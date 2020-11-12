<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixTablesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('alter table users alter column email type varchar');
        \DB::statement('alter table users alter column password type varchar;');
        \DB::statement('alter table users alter column provider type varchar;');
        \DB::statement('alter table users alter column provider_id type varchar;');
        \DB::statement('alter table password_resets alter column email type varchar;');
        \DB::statement('alter table password_resets alter column token type varchar;');
        \DB::statement('alter table roles alter column name type varchar;');
        \DB::statement('alter table roles alter column label type varchar;');
        \DB::statement('alter table permissions alter column name type varchar;');
        \DB::statement('alter table permissions alter column label type varchar;');
        \DB::statement('alter table suggested_services alter column title type varchar;');
        \DB::statement('alter table suggested_services alter column img type varchar;');
        \DB::statement('alter table what_they_say alter column img type varchar;');
        \DB::statement('alter table what_they_say alter column name type varchar;');
        \DB::statement('alter table donations_categories alter column name type varchar;');
        \DB::statement('alter table donations alter column name type varchar;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
