<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('vdlp_rssfetcher_items', static function (Blueprint $table): void {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('source_id');
            $table->string('item_id', 191)->unique('item_id_unique');
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('author')->nullable();
            $table->mediumText('category')->nullable();
            $table->string('comments')->nullable();
            $table->string('enclosure_type')->nullable();
            $table->unsignedInteger('enclosure_length')->nullable();
            $table->mediumText('enclosure_url')->nullable();
            $table->dateTimeTz('pub_date')->nullable();
            $table->boolean('publish_new_items')->default(true);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->foreign('source_id')
                ->references('id')
                ->on('vdlp_rssfetcher_sources')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vdlp_rssfetcher_items');
    }
}
