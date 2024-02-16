<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRcareOrgs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rcare_admin.rcare_orgs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 40)->nullable()->default(Null);
            $table->string('uid', 40)->nullable()->default(Null);  
            $table->string('add1', 40)->nullable()->default(Null);  
            $table->string('add2', 40)->nullable()->default(Null);
            $table->string('city', 40)->nullable()->default(Null);  
            $table->string('state', 40)->nullable()->default(Null); 
            $table->string('zip', 10)->nullable()->default(Null); 
            $table->string('phone', 20)->nullable()->default(Null); 
            $table->string('email', 30)->nullable()->default(Null);   
            $table->integer('category')->nullable()->default(Null);
            $table->string('contact_person', 40)->nullable()->default(Null); 
            $table->string('contact_person_phone', 20)->nullable()->default(Null); 
            $table->string('contact_person_email', 30)->nullable()->default(Null); 
            $table->string('schema_prefix', 10)->nullable()->default(Null); 
               
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
        Schema::dropIfExists('rcare_admin.rcare_orgs');
    }
}
