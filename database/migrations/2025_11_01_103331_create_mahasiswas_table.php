<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('prodi');
            $table->integer('semester');
            $table->decimal('ipk', 3, 2);
            $table->decimal('penghasilan_ortu', 15, 2);
            $table->integer('jumlah_tanggungan');
            $table->integer('prestasi')->default(1);
            $table->string('khs_file')->nullable();
            $table->string('penghasilan_file')->nullable();
            $table->string('sertifikat_file')->nullable();
            $table->foreignId('periode_id')->constrained('periode_seleksis');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mahasiswas');
    }
};