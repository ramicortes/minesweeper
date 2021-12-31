<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdjacentMinesCounterToCells extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cells', function (Blueprint $table) {
            $table->integer('adjacent_mines')->after('mine')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('cells', 'adjacent_mines')) {
            Schema::table('cells', function (Blueprint $table) {
                $table->dropColumn('adjacent_mines');
            });
        }
    }
}
