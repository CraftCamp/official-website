<?php

namespace Tests\Entity\Community;

use App\Entity\Community\Community;
use App\Entity\Picture;

class CommunityTest extends \PHPUnit\Framework\TestCase
{
    public function testEntity()
    {
        $community =
            (new Community())
            ->setId(1)
            ->setName('Symfony')
            ->setSlug('symfony')
            ->setPicture((new Picture()))
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt((new \DateTime()))
        ;
        $this->assertEquals(1, $community->getId());
        $this->assertEquals('Symfony', $community->getName());
        $this->assertEquals('symfony', $community->getSlug());
        $this->assertInstanceOf(Picture::class, $community->getPicture());
        $this->assertInstanceOf(\DateTime::class, $community->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $community->getUpdatedAt());
    }
    
    public function testPrePersist()
    {
        $community = new Community();
        $community->prePersist();
        
        $this->assertInstanceOf(\DateTime::class, $community->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $community->getUpdatedAt());
    }
    
    public function testPreUpdate()
    {
        $community = new Community();
        $community->preUpdate();
        
        $this->assertInstanceOf(\DateTime::class, $community->getUpdatedAt());
    }
}