<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug', 120)->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('slug', 160)->nullable();
        });

        $this->backfillSlugs('categories', 'category');
        $this->backfillSlugs('products', 'product');

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('slug');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }

    private function backfillSlugs(string $table, string $fallback): void
    {
        $usedSlugs = [];

        foreach (DB::table($table)->select(['id', 'name'])->orderBy('id')->cursor() as $record) {
            $baseSlug = Str::slug($record->name) ?: "{$fallback}-{$record->id}";
            $slug = $baseSlug;
            $suffix = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = "{$baseSlug}-{$suffix}";
                $suffix++;
            }

            DB::table($table)->where('id', $record->id)->update(['slug' => $slug]);
            $usedSlugs[] = $slug;
        }
    }
};
