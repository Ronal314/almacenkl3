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
        Schema::create('ingresos', function (Blueprint $table) {
            $table->increments('id_ingreso');
            $table->string('n_factura');
            $table->string('n_pedido');
            $table->timestamp('fecha_hora');
            $table->decimal('total', 10, 2)->check('total >= 0');
            $table->enum('estado', ['completado', 'cancelado', 'pendiente']);
            $table->unsignedInteger('id_proveedor');
            $table->unsignedInteger('id_usuario');
            $table->timestamps();
            $table->foreign('id_proveedor')->references('id_proveedor')
                ->on('proveedores')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_usuario')->references('id')
                ->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['n_factura', 'id_proveedor', 'n_pedido']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
