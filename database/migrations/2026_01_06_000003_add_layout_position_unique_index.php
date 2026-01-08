<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layouts', function (Blueprint $table): void {
            $table->unique(
                ['organization_id', 'layout_category_id', 'position'],
                'layouts_org_category_position_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('layouts', function (Blueprint $table): void {
            $table->dropUnique('layouts_org_category_position_unique');
        });
    }
};
