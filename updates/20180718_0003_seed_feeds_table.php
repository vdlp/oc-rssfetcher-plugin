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
        Schema::create('vdlp_rssfetcher_feeds', static function (Blueprint $table): void {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->enum('type', ['rss', 'atom']);
            $table->string('title');
            $table->string('description');
            $table->string('path', 191)->unique('feeds_path_unique');
            $table->unsignedTinyInteger('max_items');
            $table->boolean('is_enabled');
            $table->timestamps();
        });

        Schema::create('vdlp_rssfetcher_feeds_sources', static function (Blueprint $table): void {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('feed_id');
            $table->unsignedInteger('source_id');
            $table->foreign('feed_id', 'vdlp_rssfetcher_feed_id_foreign')
                ->references('id')
                ->on('vdlp_rssfetcher_feeds')
                ->onDelete('cascade');
            $table->foreign('source_id', 'vdlp_rssfetcher_source_id_foreign')
                ->references('id')
                ->on('vdlp_rssfetcher_sources')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vdlp_rssfetcher_feeds_sources');
        Schema::dropIfExists('vdlp_rssfetcher_feeds');
    }
};
