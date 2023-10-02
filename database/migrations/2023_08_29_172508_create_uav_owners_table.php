<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUavOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uav_owners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('email', 150)->index();
            $table->bigInteger('msisdn')->index();
            $table->float('credits');
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
        Schema::dropIfExists('uav_owners');
    }
}
