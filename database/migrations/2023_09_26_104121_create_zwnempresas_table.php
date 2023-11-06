<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('zwnempresa', function (Blueprint $table) {
            $table->id('IDEMPRESA');
            $table->string('NOME', 255);
            $table->string('APELIDO', 255);
            $table->tinyInteger('ATIVO');
            $table->string('RECCREATEDBY', 255);
            $table->datetime('RECCREATEDON');
            $table->string('RECMODIFIEDBY', 255);
            $table->datetime('RECMODIFIEDON');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zwnempresas');
    }
};
