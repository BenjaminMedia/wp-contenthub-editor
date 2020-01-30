<?php

namespace Bonnier\WP\ContentHub\Editor\Database\Migrations;

use Bonnier\WP\ContentHub\Editor\Database\DB;
use Illuminate\Database\Schema\Blueprint;

class CreateFeatureDatesTable implements Migration
{
    public static function migrate()
    {
        if (self::verify()) {
            return;
        }

        DB::instance()->getSchemaBuilder()->create('feature_dates', function (Blueprint $table) {
            $table->integer('post_id')->unsigned()->unique()->index();
            $table->timestamp('timestamp');
        });
    }

    public static function verify(): bool
    {
        return DB::instance()->getSchemaBuilder()->hasTable('feature_dates');
    }
}