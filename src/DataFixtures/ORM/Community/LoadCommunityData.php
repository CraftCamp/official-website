<?php

namespace App\DataFixtures\ORM\Community;

use App\Entity\Community\Community;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Persistence\ObjectManager;

use App\Utils\Slugger;

class LoadCommunityData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /** @var Slugger **/
    protected $slugger;
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->slugger = $container->get(Slugger::class);
    }
    
    public function load(ObjectManager $om)
    {
        $communities = include(dirname(__DIR__) . '/fixtures/communities.php');
        
        foreach ($communities as $data) {
            $community =
                (new Community())
                ->setName($data['name'])
                ->setSlug($this->slugger->slugify($data['name']))
            ;
            $om->persist($community);
            $om->flush($community);
        }
        $om->clear();
    }
    
    public function getOrder()
    {
        return 1;
    }
}