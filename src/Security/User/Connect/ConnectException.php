<?php

namespace App\Security\User\Connect;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ConnectException extends AuthenticationException
{
    /** @var string **/
    protected $service;
    
    public function setService(string $service): ConnectException
    {
        $this->service = $service;
        
        return $this;
    }
    
    public function getService(): string
    {
        return $this->service;
    }
}