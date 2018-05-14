<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Project\{
    Project,
    News
};

class NewsManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function getProjectNews(Project $project): array
    {
        return $this->em->getRepository(News::class)->findByProject($project, ['createdAt' => 'DESC']);
    }
    
    public function create(Project $project, string $category, array $data): News
    {
        $news =
            (new News())
            ->setProject($project)
            ->setCategory($category)
            ->setData($data)
        ;
        $this->em->persist($news);
        $this->em->flush();
        return $news;
    }
}