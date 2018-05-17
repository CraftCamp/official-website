<?php

namespace Tests\Entity\Community;

use App\Entity\Community\Community;
use App\Entity\Community\News;

class NewsTest extends \PHPUnit\Framework\TestCase
{
    public function testEntity()
    {
        $news =
            (new News())
            ->setId(1)
            ->setCommunity((new Community()))
            ->setCategory(News::CATEGORY_NEW_MEMBER)
            ->setData([
                'username' => 'Foo'
            ])
            ->setCreatedAt(new \DateTime())
        ;
        $this->assertEquals(1, $news->getId());
        $this->assertInstanceOf(Community::class, $news->getCommunity());
        $this->assertEquals(News::CATEGORY_NEW_MEMBER, $news->getCategory());
        $this->assertEquals([
            'username' => 'Foo'
        ], $news->getData());
        $this->assertInstanceOf(\DateTime::class, $news->getCreatedAt());
    }
}