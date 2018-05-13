<?php

namespace App\Manager\Community;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Community\{
    Community,
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
    
    public function getCommunityNews(Community $community): array
    {
        return $this->em->getRepository(News::class)->findByCommunity($community, ['createdAt' => 'DESC']);
    }
    
    public function create(Community $community, string $category, array $data): News
    {
        $news =
            (new News())
            ->setCommunity($community)
            ->setCategory($category)
            ->setData($data)
        ;
        $this->em->persist($news);
        $this->em->flush();
        return $news;
    }
}