<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vdlp_rssfetcher_items', static function (Blueprint $table): void {
            $table->dropColumn('publish_new_items');
        });

        Schema::table('vdlp_rssfetcher_sources', static function (Blueprint $table): void {
            $table->boolean('publish_new_items')
                ->after('is_enabled')
                ->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('vdlp_rssfetcher_sources', static function (Blueprint $table): void {
            $table->dropColumn('publish_new_items');
        });

        Schema::table('vdlp_rssfetcher_items', static function (Blueprint $table): void {
            $table->boolean('publish_new_items')
                ->after('is_published')
                ->default(true);
        });
    }
};
