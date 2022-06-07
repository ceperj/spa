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
        Schema::create('irpfs', function (Blueprint $table) {
            $table->id();
            $table->integer('min_cents')->comment('R$ 000000.00 (0-99999999)');
            $table->integer('max_cents')->comment('R$ 000000.00 (0-99999999)');
            $table->integer('aliquot')->comment('000.00% (0-99999)');
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('irpfs');
    }
};
