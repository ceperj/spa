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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 192)->index();
            $table->string('cpf', 14)->index();
            $table->string('rg');
            $table->string('rgexp');
            $table->string('pis');
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('bank_id')->constrained();
            $table->string('bank_agency')->constrained();
            $table->string('bank_account')->constrained();
            $table->foreignId('battery_id')->constrained();
            $table->string('email')->nullable();
            $table->foreignId('job_id')->constrained();
            $table->integer('status')->index();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('persons');
    }
};
