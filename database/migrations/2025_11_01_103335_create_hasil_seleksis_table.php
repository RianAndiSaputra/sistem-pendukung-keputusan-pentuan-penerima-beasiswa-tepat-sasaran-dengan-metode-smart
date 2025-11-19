<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hasil_seleksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
            $table->decimal('skor_ipk', 5, 2);
            $table->decimal('skor_penghasilan', 5, 2);
            $table->decimal('skor_tanggungan', 5, 2);
            $table->decimal('skor_prestasi', 5, 2);
            $table->decimal('total_skor', 5, 2);
            $table->integer('ranking')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_seleksis');
    }
};