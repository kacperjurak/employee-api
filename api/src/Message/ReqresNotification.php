<?php

namespace App\Message;

class ReqresNotification
{
    /**
     * @param int $id
     * @param string $login
     * @param string $position
     */
    public function __construct(private int $id, private string $login, private string $position)
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }
}
