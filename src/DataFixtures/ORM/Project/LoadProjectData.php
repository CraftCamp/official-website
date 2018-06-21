<?php

namespace App\DataFixtures\ORM\Project;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\{
    AbstractFixture,
    OrderedFixtureInterface
};
use App\Entity\Project\Project;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = include(dirname(__DIR__) . '/fixtures/projects.php');
        foreach ($data as $projectData) {
            $project =
                (new Project())
                ->setId($projectData['id'])
                ->setName($projectData['name'])
				->setDescription($projectData['description'])
                ->setSlug($projectData['slug'])
                ->setProductOwner($this->getReference("product-owner-{$projectData['product_owner_id']}"))
                ->setCreatedAt(new \DateTime($projectData['created_at']))
            ;
            $manager->persist($project);
            $this->addReference("project-{$projectData['slug']}", $project);
        }
        $manager->flush();
    }

    public function getOrder(): int
    {
        return 4;
    }
}
