<?php

namespace App\DataFixtures\ORM\Project;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\{
    AbstractFixture,
    OrderedFixtureInterface
};
use App\Entity\Project\Poll;

class LoadPollData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = include(dirname(__DIR__) . '/fixtures/polls.php');
        foreach ($data as $pollData) {
            $project = $this->getReference("project-{$pollData['project']}");
            $poll =
                (new Poll())
                ->setProject($project)
                ->setIsEnded($pollData['is_ended'])
                ->setCreatedAt(new \DateTime($pollData['created_at']))
                ->setEndedAt(new \DateTime($pollData['ended_at']))
            ;
            if ($pollData['is_approval'] === true) {
                $project->setApprovalPoll($poll);
            }
            $manager->persist($poll);
        }
        $manager->flush();
        $manager->clear(Poll::class);
    }

    public function getOrder(): int
    {
        return 5;
    }
}
