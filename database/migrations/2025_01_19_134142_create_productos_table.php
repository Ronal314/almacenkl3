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
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id_producto');
            $table->string('codigo')->unique();
            $table->string('descripcion')->nullable();
            $table->integer('stock')->default(0)->check('stock >= 0');
            $table->string('unidad');
            $table->tinyInteger('estado')->default(1)->check('estado IN (0, 1)');
            $table->unsignedInteger('id_categoria');
            $table->timestamps();
            $table->foreign('id_categoria')->references('id_categoria')
                ->on('categorias')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
