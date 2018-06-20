<?php

namespace App\Registry;

class ProjectRegistry implements RegistryInterface
{
    protected $projects = [
        'working' => [],
        'submitted' => [],
        'preparing' => []
    ];
    
    public function store(array $items = [])
    {
        foreach ($items as $project) {
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
}