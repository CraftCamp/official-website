<?php

namespace App\DataFixtures\ORM\Project;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\{
    AbstractFixture,
    OrderedFixtureInterface
};
use App\Entity\Project\Details;

class LoadDetailsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = include(dirname(__DIR__) . '/fixtures/project_details.php');
        foreach ($data as $detailsData) {
            $project = $this->getReference("project-{$detailsData['project']}");
            $details =
                (new Details())
                ->setProject($project)
                ->setNeedDescription($detailsData['need'])
                ->setTargetDescription($detailsData['target'])
                ->setGoalDescription($detailsData['goal'])
            ;
            $manager->persist($details);
        }
        $manager->flush();
        $manager->clear(Details::class);
    }

    public function getOrder(): int
    {
        return 5;
    }
}
