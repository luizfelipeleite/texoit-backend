<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        DB::connection('sqlite')->getPdo()->exec('PRAGMA foreign_keys=off;');

        Schema::connection('sqlite')->create('movies', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('title');
            $table->string('studios');
            $table->string('producers');
            $table->string('winner')->nullable();
            $table->timestamps();
        });

        DB::connection('sqlite')->getPdo()->exec('PRAGMA foreign_keys=on;');
    }


    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
