<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pago', function (Blueprint $table) {
            $table->decimal('interes_pagado_real', 12, 2)->nullable()->after('interes_periodo_calculado');
            $table->decimal('capital_pagado_real', 12, 2)->nullable()->after('interes_pagado_real');
        });
    }

    public function down(): void
    {
        Schema::table('pago', function (Blueprint $table) {
            $table->dropColumn(['interes_pagado_real', 'capital_pagado_real']);
        });
    }
};