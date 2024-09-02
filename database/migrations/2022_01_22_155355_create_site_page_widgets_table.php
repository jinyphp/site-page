<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_page_widgets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('enable')->default(1);

            $table->string('route')->nullable();
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
        Schema::dropIfExists('site_page_widgets');
    }
};
