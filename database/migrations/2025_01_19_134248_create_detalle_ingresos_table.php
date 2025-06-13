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
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->string('lote');
            $table->integer('cantidad_original')->check('cantidad_original >= 0');
            $table->integer('cantidad_disponible')->check('cantidad_disponible >= 0');
            $table->decimal('costo_u', 10, 2)->check('costo_u >= 0');
            $table->unsignedInteger('id_ingreso');
            $table->unsignedInteger('id_producto');
            $table->timestamps();
            $table->foreign('id_ingreso')->references('id_ingreso')
                ->on('ingresos')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_producto')->references('id_producto')
                ->on('productos')->onDelete('restrict')->onUpdate('cascade');
            $table->primary(['id_ingreso', 'id_producto']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ingresos');
    }
};
