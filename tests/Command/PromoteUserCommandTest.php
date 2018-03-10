<?php
namespace App\Tests\Command;

use App\Command\PromoteUserCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Manager\UserManager;
use App\Entity\User\Member;

class PromoteUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(new PromoteUserCommand($this->getUserManagerMock()));

        $command = $application->find('app:promote-user');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            'username' => 'JohnDoe',
            'role' => 'lead'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('JohnDoe was promoted to role ROLE_LEAD', $output);
    }
    
    public function getUserManagerMock()
    {
        $managerMock = $this
            ->getMockBuilder(UserManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $managerMock
            ->expects($this->any())
            ->method('promoteUser')
            ->willReturnCallback([$this, 'getPromotedUserMock'])
        ;
        return $managerMock;
    }
    
    public function getPromotedUserMock($username, $role)
    {
        return
            (new Member())
            ->setUsername($username)
            ->setRoles([[
                'lead' => 'ROLE_LEAD',
                'admin' => 'ROLE_ADMIN',
            ][$role]])
        ;
    }
}