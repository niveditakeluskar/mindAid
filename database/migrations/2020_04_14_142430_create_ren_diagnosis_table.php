<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenDiagnosisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ren_core.diagnosis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('condition');
            $table->json('symptoms');
            $table->json('goals');
            $table->json('tasks');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable;
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('ren_core.diagnosis');
    }
}
