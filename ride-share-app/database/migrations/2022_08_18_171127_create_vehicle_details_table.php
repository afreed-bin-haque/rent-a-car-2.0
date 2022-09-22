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
        Schema::create('vehicle_details', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_id')->nullable();
            $table->string('mileage')->nullable();
            $table->string('plate')->nullable();
            $table->string('condition')->nullable();
            $table->string('model')->nullable();
            $table->string('type')->nullable();
            $table->string('seats')->nullable();
            $table->string('color')->nullable();
            $table->string('main_image')->nullable();
            $table->string('author')->nullable();
            $table->string('status')->default('Inactive');
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
        Schema::dropIfExists('vehicle_details');
    }
};
