<?php

namespace App\Gateway;

use GuzzleHttp\Client;

use App\Entity\Project\Repository;

class GithubGateway
{
    /** @var Client **/
    protected $client;
    /** @var string **/
    protected $clientId;
    /** @var string **/
    protected $clientSecret;
    
    public function __construct(string $baseUri, string $clientId, string $clientSecret)
    {
        $this->client = new Client([
            'base_uri' => $baseUri
        ]);
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }
    
    public function createRepository(Repository $repository)
    {
        $this->client->post("/orgs/{$repository->getOwner()}/repos", [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'name' => $repository->getName(),
                'description' => $repository->getDescription()
            ])
        ]);
    }
    
    public function getRepository(string $owner, string $name)
    {
        return $this->client->get("/repos/$owner/$name");
    }
}