<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zwnclicontatos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('IDCLIENTE'); // Chave estrangeira
            $table->string('NOME', 255);
            $table->string('APELIDO', 255);
            $table->string('TELEFONE', 15);
            $table->string('CELULAR', 15);
            $table->string('EMAIL', 60);
            $table->tinyInteger('ATIVO');
            $table->string('RECCREATEDBY', 255);
            $table->datetime('RECCREATEDON');
            $table->string('RECMODIFIEDBY', 255);
            $table->datetime('RECMODIFIEDON');
                        
            // Definindo a chave estrangeira
            $table->foreign('IDCLIENTE')->references('IDCLIENTE')->on('zwnccliente');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zwnclicontatos');
    }
};

