<?php


namespace Bonnier\WP\ContentHub\Editor\Database\Migrations;


interface Migration
{
    public static function migrate();
    public static function verify(): bool;
}