<?php

namespace App\Registry;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use App\Entity\User\User;

class ProjectRegistry implements RegistryInterface
{
    /** @var TokenStorageInterface **/
    protected $tokenStorage;
    
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    
    protected $projects = [
        'owned' => [],
        'working' => [],
        'submitted' => [],
        'preparing' => []
    ];
    
    public function store(array $items = [])
    {
        $user = $this->getUser();
        foreach ($items as $project) {
            if ($project->getProductOwner() === $user) {
                $this->projects['owned'][] = $project;
                continue;
            }
            $poll = $project->getApprovalPoll();
            if ($poll !== null && $poll->getIsEnded() === true) {
                $this->projects['working'][] = $project;
            } elseif ($poll !== null) {
                $this->projects['submitted'][] = $project;
            } else {
                $this->projects['preparing'][] = $project;
            }
        }
    }
    
    public function getItems(): array
    {
        return $this->projects;
    }
    
    public function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();
        return
            ($token !== null && $token->getUser() instanceof User)
            ? $token->getUser()
            : null
        ;
        ;
    }
}