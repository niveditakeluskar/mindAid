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
        Schema::create('patient_contact_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id')->nullable();
            $table->smallInteger('mon_0')->default(0);
            $table->smallInteger('mon_1')->default(0);
            $table->smallInteger('mon_2')->default(0);
            $table->smallInteger('mon_3')->default(0);
            $table->smallInteger('mon_any')->default(0);
            $table->smallInteger('tue_0')->default(0);
            $table->smallInteger('tue_1')->default(0);
            $table->smallInteger('tue_2')->default(0);
            $table->smallInteger('tue_3')->default(0);
            $table->smallInteger('tue_any')->default(0);
            $table->smallInteger('wed_0')->default(0);
            $table->smallInteger('wed_1')->default(0);
            $table->smallInteger('wed_2')->default(0);
            $table->smallInteger('wed_3')->default(0);
            $table->smallInteger('wed_any')->default(0);
            $table->smallInteger('thu_0')->default(0);
            $table->smallInteger('thu_1')->default(0);
            $table->smallInteger('thu_2')->default(0);
            $table->smallInteger('thu_3')->default(0);
            $table->smallInteger('thu_any')->default(0);
            $table->smallInteger('fri_0')->default(0);
            $table->smallInteger('fri_1')->default(0);
            $table->smallInteger('fri_2')->default(0);
            $table->smallInteger('fri_3')->default(0);
            $table->smallInteger('fri_any')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->smallInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_contact_times');
    }
};
