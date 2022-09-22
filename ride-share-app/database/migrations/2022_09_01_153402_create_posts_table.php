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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_id')->nullable();
            $table->string('from_loc')->nullable();
            $table->string('to_loc')->nullable();
            $table->string('vehicle_id')->nullable();
            $table->string('plate')->nullable();
            $table->string('jurney_date')->nullable();
            $table->string('seat')->nullable();
            $table->string('price_per_seat')->nullable();
            $table->string('total_fare')->nullable();
            $table->string('author')->nullable();
             $table->string('status')->default('Approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
