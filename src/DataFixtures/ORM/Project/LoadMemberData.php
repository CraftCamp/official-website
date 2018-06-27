<?php

namespace App\DataFixtures\ORM\Project;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\{
    AbstractFixture,
    OrderedFixtureInterface
};
use App\Entity\Project\Member;

class LoadMemberData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = include(dirname(__DIR__) . '/fixtures/project_members.php');
        foreach ($data as $memberData) {
            $project = $this->getReference("project-{$memberData['project']}");
            $membership =
                (new Member())
                ->setProject($project)
                ->setUser($this->getReference("product-owner-{$memberData['user_id']}"))
                ->setIsActive($memberData['is_active'])
                ->setCreatedAt(new \DateTime($memberData['created_at']))
                ->setUpdatedAt(new \DateTime($memberData['updated_at']))
            ;
            $manager->persist($membership);
        }
        $manager->flush();
        $manager->clear(Member::class);
    }

    public function getOrder(): int
    {
        return 6;
    }
}
