<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hasil_seleksis', function (Blueprint $table) {
            // Tambah kolom periode_id
            $table->foreignId('periode_id')->after('mahasiswa_id')->nullable();
            
            // Add foreign key constraint
            $table->foreign('periode_id')
                  ->references('id')
                  ->on('periode_seleksis')
                  ->onDelete('cascade');
        });

        // Update data existing: copy periode_id dari mahasiswa ke hasil_seleksi
        DB::statement("
            UPDATE hasil_seleksis hs
            JOIN mahasiswas m ON hs.mahasiswa_id = m.id
            SET hs.periode_id = m.periode_id
            WHERE hs.periode_id IS NULL
        ");
    }

    public function down()
    {
        Schema::table('hasil_seleksis', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
};