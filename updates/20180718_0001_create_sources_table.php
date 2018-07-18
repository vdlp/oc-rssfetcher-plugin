<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

/** @noinspection AutoloadingIssuesInspection */

/**
 * Class CreateSourcesTable
 *
 * @package Vdlp\RssFetcher\Updates
 */
class CreateSourcesTable extends Migration
{
    public function up()
    {
        Schema::create('vdlp_rssfetcher_sources', function (Blueprint $table) {
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

    public function down()
    {
        Schema::dropIfExists('vdlp_rssfetcher_sources');
    }
}
