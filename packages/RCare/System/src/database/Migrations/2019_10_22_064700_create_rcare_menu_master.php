<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRcareMenuMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rcare_admin.rcare_menu_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menu', 50)->nullable()->default(Null);
            $table->string('menu_url', 50)->nullable()->default(Null);
            $table->Integer('service_id');
            $table->Integer('created_by')->nullable()->default(Null);
            $table->Integer('updated_by')->nullable()->default(Null);
            $table->timestamps();
            $table->tinyInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcare_admin.rcare_menu_master');
    }
}
