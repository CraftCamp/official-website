<?php

namespace Tests\Registry;

use App\Registry\ProjectRegistry;

use App\Entity\User\Member;
use App\Entity\Project\Project;
use App\Entity\Project\Poll;

class ProjectRegistryTest extends \PHPUnit\Framework\TestCase
{
    /** @var ProjectRegistry **/
    protected $registry;
    /** @var Member **/
    protected static $owner;
    
    public function setUp()
    {
        $this->registry = new ProjectRegistry($this->getTokenStorageMock());
    }
    
    /**
     * @dataProvider provideProjects
     */
    public function testStore($data, $expected)
    {
        $this->registry->store($data);
        
        $items = $this->registry->getItems();
        
        foreach ($expected as $key => $count) {
            $this->assertCount($count, $items[$key]);
        }
    }
    
    public function getTokenStorageMock()
    {
        $tokenStorageMock = $this->createMock(
            \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface::class
        );
        $tokenStorageMock
            ->expects($this->any())
            ->method('getToken')
            ->willReturnCallback([$this, 'getTokenMock'])
        ;
        return $tokenStorageMock;
    }
    
    public function getTokenMock()
    {
        $tokenMock = $this->createMock(
            \Symfony\Component\Security\Core\Authentication\Token\TokenInterface::class
        );
        $tokenMock
            ->expects($this->any())
            ->method('getUser')
            ->willReturn(self::getOwnerMock())
        ;
        return $tokenMock;
    }
    
    public function getUserMock()
    {
        return
            (new Member())
            ->setUsername('Toto')
        ;
    }
    
    public static function getOwnerMock()
    {
        if (self::$owner === null) {
            self::$owner = (new Member())->setUsername('Foo');
        }
        return self::$owner;
    }
    
    public function provideProjects()
    {
        return [
            [
                [
                    (new Project())
                    ->setName('')
                    ->setProductOwner($this->getUserMock())
                    ->setApprovalPoll((new Poll())->setIsEnded(true)),
                    (new Project())
                    ->setName('')
                    ->setProductOwner(self::getOwnerMock()),
                    (new Project())
                    ->setProductOwner($this->getUserMock())
                    ->setName(''),
                    (new Project())
                    ->setName('')
                    ->setProductOwner($this->getUserMock())
                    ->setApprovalPoll((new Poll())->setIsEnded(true)),
                ],
                [
                    'owned' => 1,
                    'working' => 2,
                    'submitted' => 0,
                    'preparing' => 1
                ]
            ],
            [
                [
                    (new Project())
                    ->setName('')
                    ->setProductOwner(self::getOwnerMock()),
                    (new Project())
                    ->setName('')
                    ->setProductOwner(self::getOwnerMock()),
                    (new Project())
                    ->setName('')
                    ->setProductOwner($this->getUserMock())
                    ->setApprovalPoll((new Poll())->setIsEnded(false)),
                ],
                [
                    'owned' => 2,
                    'working' => 0,
                    'submitted' => 1,
                    'preparing' => 0
                ]
            ],
            [
                [
                    (new Project())
                    ->setProductOwner($this->getUserMock())
                    ->setApprovalPoll((new Poll())->setIsEnded(true))
                    ->setName(''),
                    (new Project())
                    ->setProductOwner($this->getUserMock())
                    ->setName(''),
                    (new Project())
                    ->setProductOwner($this->getUserMock())
                    ->setName(''),
                ],
                [
                    'owned' => 0,
                    'working' => 1,
                    'submitted' => 0,
                    'preparing' => 2
                ]
            ],
        ];
    }
}