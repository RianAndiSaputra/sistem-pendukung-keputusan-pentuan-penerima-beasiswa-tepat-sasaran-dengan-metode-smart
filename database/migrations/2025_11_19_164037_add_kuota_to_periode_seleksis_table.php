<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('periode_seleksis', function (Blueprint $table) {
            $table->integer('kuota_penerima')->default(10)->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('periode_seleksis', function (Blueprint $table) {
            $table->dropColumn('kuota_penerima');
        });
    }
};