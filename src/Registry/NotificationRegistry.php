<?php

namespace App\Registry;

class NotificationRegistry
{
    protected $notifications = [];
    
    public function storeNotifications(array $notifications = [])
    {
        $this->notifications = $notifications;
    }
    
    public function getAll(): array
    {
        return $this->notifications;
    }
}