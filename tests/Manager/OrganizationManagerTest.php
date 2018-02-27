<?php

namespace Tests\Manager;

use App\Manager\OrganizationManager;

use App\Entity\Organization;

use App\Utils\Slugger;

class OrganizationManagerTest extends \PHPUnit\Framework\TestCase {
    /** @var OrganizationManager **/
    protected $manager;

    public function setUp() {
        $this->manager = new OrganizationManager($this->getEntityManagerMock(), new Slugger());
    }

    public function testCreateOrganization() {
        $organization = $this->manager->createOrganization([
            'name' => 'ONU',
            'description' => 'Salon de thé',
            'type' => 'association'
        ]);
        $this->assertEquals('ONU', $organization->getName());
        $this->assertEquals('onu', $organization->getSlug());
        $this->assertEquals(Organization::TYPE_ASSOCIATION, $organization->getType());
        $this->assertEquals('Salon de thé', $organization->getDescription());
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
