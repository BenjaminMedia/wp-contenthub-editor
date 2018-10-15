<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class User extends AbstractField
{
    /** @var string */
    protected $role;
    /** @var int */
    protected $allowNull;
    /** @var int */
    protected $multiple;

    public function __construct(string $key)
    {
        parent::__construct($key, 'user');
        $this->role = '';
        $this->allowNull = 0;
        $this->multiple = 0;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return User
     */
    public function setRole(string $role): User
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return int
     */
    public function getAllowNull(): int
    {
        return $this->allowNull;
    }

    /**
     * @param int $allowNull
     * @return User
     */
    public function setAllowNull(int $allowNull): User
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    /**
     * @return int
     */
    public function getMultiple(): int
    {
        return $this->multiple;
    }

    /**
     * @param int $multiple
     * @return User
     */
    public function setMultiple(int $multiple): User
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'role' => $this->role,
            'allow_null' => $this->allowNull,
            'multiple' => $this->multiple,
        ]);
    }
}
