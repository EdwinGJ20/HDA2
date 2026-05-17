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
    // FOROS: Temas de discusión públicos
    Schema::create('foros', function (Blueprint $table) {
        $table->id('ID_foro');
        $table->unsignedBigInteger('ID_usuario');
        $table->string('Titulo');
        $table->text('Contenido');
        $table->string('Categoria'); // Ejemplo: 'Salud Mental', 'Nutrición'
        $table->timestamps();
    });

    // DIARIOS: Notas personales privadas
    Schema::create('diarios', function (Blueprint $table) {
        $table->id('ID_diario');
        $table->unsignedBigInteger('ID_usuario');
        $table->string('Titulo')->nullable();
        $table->text('Entrada');
        $table->string('Estado_Animo')->nullable(); // Ejemplo: 'Feliz', 'Ansiosa'
        $table->timestamps();
    });

    // CHATS: Mensajes directos
    Schema::create('chats', function (Blueprint $table) {
        $table->id('ID_chat');
        $table->unsignedBigInteger('ID_emisor');
        $table->unsignedBigInteger('ID_receptor');
        $table->text('Mensaje');
        $table->boolean('Leido')->default(false);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foros_chats_diarios_tables');
    }
};
