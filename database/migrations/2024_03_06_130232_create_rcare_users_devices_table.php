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
        Schema::create('rcare_users_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('reg_date');
            $table->boolean('status');
            $table->macAddress('mac_address');
            $table->ipAddress('ipv6_address');
            $table->integer('userid');
            $table->timestamps();
            $table->string('user_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcare_users_devices');
    }
};
