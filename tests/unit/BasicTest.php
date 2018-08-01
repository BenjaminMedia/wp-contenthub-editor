<?php

namespace Tests\Unit;

use BadMethodCallException;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Bonnier\WP\ContentHub\Editor\ContenthubEditor;
use WP_REST_Server;

class BasicTest extends TestCase
{
    public function testPluginCanLoad()
    {
        $contenthubEditor = ContenthubEditor::instance();

        $this->assertInstanceOf(ContenthubEditor::class, $contenthubEditor);
    }

    public function testPluginRegistersCollectionMacros()
    {
        ContenthubEditor::instance();

        $collection = new Collection();

        try {
            $this->assertInstanceOf(Collection::class, $collection->toAssocCombine());
            $this->assertInstanceOf(Collection::class, $collection->toAssoc());
            $this->assertInstanceOf(Collection::class, $collection->rejectNullValues());
            $this->assertInstanceOf(Collection::class, $collection->itemsToObject());
        } catch (BadMethodCallException $exception) {
            $this->fail($exception->getMessage());
        }
    }
}
