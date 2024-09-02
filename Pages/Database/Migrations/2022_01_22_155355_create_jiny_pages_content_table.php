<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJinyPagesContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jiny_pages_content', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('enable')->default(1);
            $table->string('route');
            $table->string('type')->nullable();
            $table->string('path')->nullable();

            $table->string('title')->nullable();
            $table->string('href')->nullable();
            $table->string('description')->nullable();

            $table->string('element')->default('section');
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('padding')->nullable();
            $table->string('margin')->nullable();
            $table->string('rounding')->nullable();
            $table->string('background')->nullable();

            $table->integer('pos')->default(0);
            $table->integer('ref')->default(0);
            $table->integer('level')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jiny_pages_markdown');
    }
}
