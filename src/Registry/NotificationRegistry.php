<?php

namespace App\Registry;

class NotificationRegistry implements RegistryInterface
{
    protected $notifications = [];
    
    public function store(array $items = [])
    {
        $this->notifications = $items;
    }
    
    public function getItems(): array
    {
        return $this->notifications;
    }
}