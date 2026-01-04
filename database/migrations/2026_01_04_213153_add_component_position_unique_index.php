<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('components', function (Blueprint $table) {
            $table->unique(
                ['organization_id', 'component_category_id', 'position'],
                'components_org_category_position_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('components', function (Blueprint $table) {
            $table->dropUnique('components_org_category_position_unique');
        });
    }
};
