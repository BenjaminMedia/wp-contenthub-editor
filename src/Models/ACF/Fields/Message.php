<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Message extends AbstractField
{
    /** @var string */
    protected $message;
    /** @var string */
    protected $newLines;
    /** @var int */
    protected $escapeHTML;

    public function __construct(string $key)
    {
        parent::__construct($key, 'message');
        $this->message = '';
        $this->newLines = '';
        $this->escapeHTML = 0;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function setMessage(string $message): Message
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewLines(): string
    {
        return $this->newLines;
    }

    /**
     * @param string $newLines
     * @return Message
     */
    public function setNewLines(string $newLines): Message
    {
        $this->newLines = $newLines;
        return $this;
    }

    /**
     * @return int
     */
    public function getEscapeHTML(): int
    {
        return $this->escapeHTML;
    }

    /**
     * @param int $escapeHTML
     * @return Message
     */
    public function setEscapeHTML(int $escapeHTML): Message
    {
        $this->escapeHTML = $escapeHTML;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'message' => $this->message,
            'new_lines' => $this->newLines,
            'esc_html' => $this->escapeHTML,
        ]);
    }
}
