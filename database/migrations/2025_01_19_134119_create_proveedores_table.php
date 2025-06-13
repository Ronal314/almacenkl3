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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id_proveedor');
            $table->string('razon_social')->nullable();
            $table->string('nombre');
            $table->string('nit')->unique()->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->tinyInteger('estado')->default(1)->check('estado IN (0, 1)')->notNullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
