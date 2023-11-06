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
    Schema::create('zwnusuarios', function (Blueprint $table) {
        $table->bigIncrements('IDUSUARIO'); // Renomeie o ID para IDUSUARIO
        $table->string('NOME');
        $table->string('APELIDO');
        $table->string('USUARIO');
        $table->string('SENHA');
        $table->string('EMAIL');
        $table->boolean('ATIVO');
        $table->string('RECCREATEDBY');
        $table->timestamp('RECCREATEDON')->useCurrent();
        $table->string('RECMODIFIEDBY');
        $table->timestamp('RECMODIFIEDON')->useCurrent();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zwnusuarios');
    }
};
