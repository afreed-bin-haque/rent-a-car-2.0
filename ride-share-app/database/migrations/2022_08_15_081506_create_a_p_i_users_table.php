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
        Schema::create('a_p_i_users', function (Blueprint $table) {
            $table->id();
            $table->string('api_user')->nullable();
            $table->string('token')->nullable();
            $table->string('access')->nullable();
            $table->string('limit')->nullable();
            $table->string('limit_range')->nullable();
            $table->string('status')->default('Active');
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
        Schema::dropIfExists('a_p_i_users');
    }
};
