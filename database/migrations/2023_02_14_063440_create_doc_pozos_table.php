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
        Schema::create('doc_pozos', function (Blueprint $table) {
            $table->id();
            $table->mediumText('documento');
            $table->foreignId('pozo_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->date('fecha_hora');
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
        Schema::dropIfExists('doc_pozos');
    }
};
