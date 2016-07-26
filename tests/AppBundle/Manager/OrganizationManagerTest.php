<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Manager\OrganizationManager;

use AppBundle\Entity\Organization;

use AppBundle\Utils\Slugger;

class OrganizationManagerTest extends \PHPUnit_Framework_TestCase {
    /** @var OrganizationManager **/
    protected $manager;

    public function setUp() {
        $this->manager = new OrganizationManager($this->getEntityManagerMock(), new Slugger());
    }

    public function testCreateOrganization() {
        $organization = (new Organization)->setName('ONU');
        $this->manager->createOrganization($organization);

        $this->assertEquals('onu', $organization->getSlug());
    }

    public function getEntityManagerMock() {
        $entityManagerMock = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true)
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('flush')
            ->willReturn(true)
        ;
        return $entityManagerMock;
    }
}
