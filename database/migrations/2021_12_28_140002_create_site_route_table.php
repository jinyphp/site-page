<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_route', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('enable')->default(1);

            $table->string('route')->nullable();
            $table->string('type')->nullable(); // view, post, markdown
            $table->string('page')->nullable();


            $table->string('description')->nullable();
            // 작업자ID
            $table->unsignedBigInteger('user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_route');
    }
}
