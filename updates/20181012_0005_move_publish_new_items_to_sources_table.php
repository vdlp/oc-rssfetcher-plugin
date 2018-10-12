<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

/** @noinspection AutoloadingIssuesInspection */

/**
 * Class MovePublishNewItemsToSourcesTable
 *
 * @package Vdlp\RssFetcher\Updates
 */
class MovePublishNewItemsToSourcesTable extends Migration
{
    public function up()
    {
        Schema::table('vdlp_rssfetcher_items', function (Blueprint $table) {
            $table->dropColumn('publish_new_items');
        });

        Schema::table('vdlp_rssfetcher_sources', function (Blueprint $table) {
            $table->boolean('publish_new_items')
                ->after('is_enabled')
                ->default(true);
        });

    }

    public function down()
    {
        Schema::table('vdlp_rssfetcher_sources', function (Blueprint $table) {
            $table->dropColumn('publish_new_items');
        });

        Schema::table('vdlp_rssfetcher_items', function (Blueprint $table) {
            $table->boolean('publish_new_items')
                ->after('is_published')
                ->default(true);
        });
    }
}
