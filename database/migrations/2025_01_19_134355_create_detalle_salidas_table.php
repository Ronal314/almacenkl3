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
        Schema::create('detalle_salidas', function (Blueprint $table) {
            $table->string('lote');
            $table->integer('cantidad')->check('cantidad  >= 0');
            $table->decimal('costo_u', 10, 2)->check('costo_u >= 0');
            $table->unsignedInteger('id_salida');
            $table->unsignedInteger('id_producto');
            $table->timestamps();
            $table->foreign('id_salida')->references('id_salida')
                ->on('salidas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_producto')->references('id_producto')
                ->on('productos')->onDelete('restrict')->onUpdate('cascade');
            $table->primary(['id_salida', 'id_producto', 'lote']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_salidas');
    }
};
