<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateSourcesTable extends Migration
{
    public function up(): void
    {
        Schema::create('vdlp_rssfetcher_sources', static function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 40)->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('source_url');
            $table->smallInteger('max_items');
            $table->dateTime('fetched_at')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vdlp_rssfetcher_sources');
    }
}
