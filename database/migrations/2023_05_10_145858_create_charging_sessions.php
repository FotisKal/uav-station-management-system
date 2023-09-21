<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargingSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charging_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('charging_station_id');
            $table->string('uav_id');
            $table->dateTimeTz('date_time_start', 0);
            $table->dateTimeTz('date_time_end', 0)->nullable();
            $table->dateTimeTz('estimated_date_time_end', 0)->nullable();
            $table->float('kw_spent', 6);
            $table->string('charging_session_cost_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charging_sessions');
    }
}
