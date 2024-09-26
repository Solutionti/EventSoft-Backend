<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento');
            $table->string('documento');
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('departamento');
            $table->string('ciudad');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email')->unique();;
            $table->string('password');
            $table->string('rol_usuario');
            $table->string('acompanantes');
            $table->string('rh');
            $table->string('seguro_medico');
            $table->string('fecha_nacimiento');
            $table->string('estado');
            $table->string('fecha');
            $table->string('hora');
            $table->rememberToken();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
