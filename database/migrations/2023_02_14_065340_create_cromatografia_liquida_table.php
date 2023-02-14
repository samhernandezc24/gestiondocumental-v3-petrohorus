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
        Schema::create('cromatografia_liquida', function (Blueprint $table) {
            $table->id('idCromli');
            $table->mediumText('documento');
            $table->foreignId('idPozo')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->date('fecha_hora');
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
        Schema::dropIfExists('cromatografia_liquida');
    }
};