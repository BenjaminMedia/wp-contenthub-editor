<?php

namespace Bonnier\WP\ContentHub\Editor\Database;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;

class DB
{
    /** @var Manager */
    private static $instance;

    public static function instance(): Connection
    {
        if (!self::$instance) {
            /** @var \wpdb $wpdb */
            global $wpdb;
            $capsule = new Manager();
            $capsule->addConnection([
                'driver' => 'mysql',
                'host' => env('DB_HOST', 'localhost'),
                'database' => env('DB_NAME'),
                'username' => env('DB_USER'),
                'password' => env('DB_PASSWORD'),
                'charset' => $wpdb->charset,
                'collation' => $wpdb->collate,
                'prefix' => $wpdb->prefix
            ]);
            $capsule->bootEloquent();
            self::$instance = $capsule->getConnection();
        }

        return self::$instance;
    }
}