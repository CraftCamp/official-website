<?php

namespace App\Model\User;

abstract class ActivationLink
{
    /** @var string **/
    protected $hash;
    /** @var \DateTime **/
    protected $createdAt;

    public function setHash(string $hash): ActivationLink
    {
        $this->hash = $hash;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setCreatedAt(\DateTime $createdAt): ActivationLink
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
