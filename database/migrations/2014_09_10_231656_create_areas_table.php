<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->integer('porcentagem');
            $table->timestamps();
        });

        DB::table('areas')->insert([
            ['nome' => 'Salão', 'porcentagem' => 60],
            ['nome' => 'Retaguarda', 'porcentagem' => 40],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
