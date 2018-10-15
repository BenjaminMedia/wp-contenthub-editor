<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Tab extends AbstractField
{
    /** @var string */
    protected $placement;
    /** @var string|int */
    protected $endpoint;

    public function __construct(string $key)
    {
        parent::__construct($key, 'tab');
        $this->placement = 'top';
        $this->endpoint = 0;
    }

    /**
     * @return string
     */
    public function getPlacement(): string
    {
        return $this->placement;
    }

    /**
     * @param string $placement
     * @return Tab
     */
    public function setPlacement(string $placement): Tab
    {
        $this->placement = $placement;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param int|string $endpoint
     * @return Tab
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'placement' => $this->placement,
            'endpoint' => $this->endpoint,
        ]);
    }
}
