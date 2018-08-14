<?php

namespace App\Gateway;

use GuzzleHttp\Client;

class SchedulerGateway
{
    /** @var Client **/
    protected $client;
    
    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://craftcamp_scheduler']);
    }
    
    public function addTask(string $method, string $url, string $id, \DateTime $executedAt)
    {
        return $this->client->post('/jobs', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'id' => $id,
                'method' => $method,
                'url' => $url,
                'executed_at' => $executedAt->format('c')
            ])
        ]);
    }
}