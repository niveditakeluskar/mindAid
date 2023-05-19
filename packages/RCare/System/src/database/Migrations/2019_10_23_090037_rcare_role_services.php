<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RcareRoleServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rcare_admin.rcare_role_services', function (Blueprint $table) {
            $table->bigIncrements('id');
	    $table->string('role_id');
	    $table->string('service_id');
	    $table->string('crud');	
	    $table->string('status');						
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
        Schema::dropIfExists('rcare_admin.rcare_role_services');
    }
}
