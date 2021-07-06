<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class RemoveUniqueConstraintFromItems extends Migration
{
    public function up(): void
    {
        Schema::table('vdlp_rssfetcher_items', static function (Blueprint $table) {
            $table->dropUnique('item_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('vdlp_rssfetcher_items', static function (Blueprint $table) {
            $table->unique('item_id', 'item_id_unique');
        });
    }
}
