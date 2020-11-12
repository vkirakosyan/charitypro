<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveViewsColumnFromForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forums', function (Blueprint $table) {
            if (Schema::hasColumn('forums', 'views')) {
                $table->dropColumn('views');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forums', function (Blueprint $table) {
            if (!Schema::hasColumn('forums', 'views')) {
                $table->bigInteger('views')->unsigned()->default(0);
            }
        });
    }
}
