<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUavs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uavs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('owner_id');
            $table->string('company_id');
            $table->string('name');
            $table->integer('charging_percentage')->nullable();
            $table->text('position_json')->nullable();
            $table->string('api_token', 80)->unique()->nullable();
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
        Schema::dropIfExists('uavs');
    }
}
