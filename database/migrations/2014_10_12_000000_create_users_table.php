<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role_id')->index();
            $table->string('name')->index();
            $table->string('email', 150)->index();
            $table->bigInteger('msisdn')->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('date_format');
            $table->string('datetime_format');
            $table->boolean('debug');
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
