<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aviso_remate', function (Blueprint $table) {
            $table->decimal('precio_ofertado', 12, 2)->nullable()->after('id_prenda');
        });

        Schema::table('remate', function (Blueprint $table) {
            $table->string('categoria', 40)->nullable()->after('id_prenda');
        });
    }

    public function down(): void
    {
        Schema::table('aviso_remate', function (Blueprint $table) {
            $table->dropColumn('precio_ofertado');
        });

        Schema::table('remate', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
