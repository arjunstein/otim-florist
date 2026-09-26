<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->string('google_reviews_url', 500)->nullable();
            $table->decimal('google_rating', 3, 1)->nullable()->default(5.0);
            $table->unsignedInteger('google_reviews_count')->nullable()->default(27);
        });

        DB::table('store_settings')->where('id', 1)->update([
            'google_reviews_url' => 'https://share.google/v4xDWRoD4ANg5Ft3K',
            'google_rating' => 5.0,
            'google_reviews_count' => 27,
        ]);
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn(['google_reviews_url', 'google_rating', 'google_reviews_count']);
        });
    }
};
