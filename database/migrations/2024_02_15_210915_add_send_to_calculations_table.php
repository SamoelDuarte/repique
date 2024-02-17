<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendToCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calculo', function (Blueprint $table) {
            $table->boolean('send')->default(false); // Adiciona a nova coluna booleana 'send' com valor padrão false
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calculo', function (Blueprint $table) {
            $table->dropColumn('send'); // Reverte a migração, removendo a coluna 'send' se existir
        });
    }
}

