<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('cells')) {
            Schema::create('cells', function (Blueprint $table) {
                $table->id();
                $table->integer('row');
                $table->integer('column');
                $table->string('state'); // covered, uncovered, flagged
                $table->string('flagged')->nullable(); // null, flag, mark
                $table->boolean('mine')->default(false);
                $table->unsignedBigInteger('game_id');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cells');
    }
}
