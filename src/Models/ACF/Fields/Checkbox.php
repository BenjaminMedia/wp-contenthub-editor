<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Checkbox extends AbstractField
{
    protected $message;
    protected $defaultValue;
    protected $userInterface;
    protected $uiOnText;
    protected $uiOffText;

    public function __construct(string $key)
    {
        parent::__construct($key, 'true_false');
        $this->message = '';
        $this->defaultValue = 0;
        $this->userInterface = 0;
        $this->uiOnText = '';
        $this->uiOffText = '';
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
     * @return Checkbox
     */
    public function setMessage(string $message): Checkbox
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultValue(): int
    {
        return $this->defaultValue;
    }

    /**
     * @param int $defaultValue
     * @return Checkbox
     */
    public function setDefaultValue(int $defaultValue): Checkbox
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserInterface(): int
    {
        return $this->userInterface;
    }

    /**
     * @param int $userInterface
     * @return Checkbox
     */
    public function setUserInterface(int $userInterface): Checkbox
    {
        $this->userInterface = $userInterface;
        return $this;
    }

    /**
     * @return string
     */
    public function getUiOnText(): string
    {
        return $this->uiOnText;
    }

    /**
     * @param string $uiOnText
     * @return Checkbox
     */
    public function setUiOnText(string $uiOnText): Checkbox
    {
        $this->uiOnText = $uiOnText;
        return $this;
    }

    /**
     * @return string
     */
    public function getUiOffText(): string
    {
        return $this->uiOffText;
    }

    /**
     * @param string $uiOffText
     * @return Checkbox
     */
    public function setUiOffText(string $uiOffText): Checkbox
    {
        $this->uiOffText = $uiOffText;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'message' => $this->message,
            'default_value' => $this->defaultValue,
            'ui' => $this->userInterface,
            'ui_on_text' => $this->uiOnText,
            'ui_off_text' => $this->uiOffText,
        ]);
    }
}
