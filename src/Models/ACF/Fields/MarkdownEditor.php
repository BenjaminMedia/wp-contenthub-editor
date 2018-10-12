<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class MarkdownEditor extends AbstractField
{
    protected $config;
    protected $fontSize;

    public function __construct(string $key)
    {
        parent::__construct($key, 'markdown-editor');
        $this->config = 'standard';
        $this->fontSize = 14;
    }

    /**
     * @return string
     */
    public function getConfig(): string
    {
        return $this->config;
    }

    /**
     * @param string $config
     * @return MarkdownEditor
     */
    public function setConfig(string $config): MarkdownEditor
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return int
     */
    public function getFontSize(): int
    {
        return $this->fontSize;
    }

    /**
     * @param int $fontSize
     * @return MarkdownEditor
     */
    public function setFontSize(int $fontSize): MarkdownEditor
    {
        $this->fontSize = $fontSize;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'simple_mde_config' => $this->config,
            'font_size' => $this->fontSize,
        ]);
    }
}
