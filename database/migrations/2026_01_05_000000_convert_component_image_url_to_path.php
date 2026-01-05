<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('components')
            ->select(['id', 'image_url'])
            ->orderBy('id')
            ->chunkById(100, function ($components): void {
                foreach ($components as $component) {
                    $value = (string) $component->image_url;
                    $path = $this->normalizePath($value);

                    if ($path !== $value && $path !== '') {
                        DB::table('components')
                            ->where('id', $component->id)
                            ->update(['image_url' => $path]);
                    }
                }
            });
    }

    public function down(): void
    {
        //
    }

    private function normalizePath(string $value): string
    {
        if ($value === '') {
            return $value;
        }

        if (Str::startsWith($value, '/storage/')) {
            return ltrim(Str::after($value, '/storage/'), '/');
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $path = parse_url($value, PHP_URL_PATH) ?: '';
            if (Str::contains($path, '/storage/')) {
                return ltrim(Str::after($path, '/storage/'), '/');
            }

            return ltrim($path, '/');
        }

        return ltrim($value, '/');
    }
};
