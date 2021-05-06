<?php

namespace App\Client\Entity;

class ClientSearch
{

    /**
     * clientCode
     *
     * @var int|null
     */
    private $clientCode;

    public function getClientCode(): ?int
    {
        return $this->clientCode;
    }

    public function setClientCode(?int $clientCode)
    {
        $this->clientCode = $clientCode;
    }
}
