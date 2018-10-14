<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class ImageHotspotCoordinate extends AbstractField
{
    public function __construct(string $key)
    {
        parent::__construct($key, 'image-hotspot-coordinates');
    }
}
