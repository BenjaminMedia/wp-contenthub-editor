<?php

namespace Bonnier\WP\ContentHub\Editor;

trait Singleton
{
    protected static $instance;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}

