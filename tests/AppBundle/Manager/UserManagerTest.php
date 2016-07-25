<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Manager\{ActivationLinkManager, UserManager};

use AppBundle\Repository\User\UserRepository;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\Form;

use AppBundle\Entity\User\{ProductOwner, ActivationLink};

use Symfony\Component\Translation\Translator;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserManagerTest extends \PHPUnit_Framework_TestCase {
    /** @var \AppBundle\Manager\UserManager **/
    protected $manager;

    public function setUp() {
        $this->manager = new UserManager(
            $this->getEntityManagerMock(),
            $this->getEncoderMock(),
            $this->getTranslatorMock(),
            $this->getActivationLinkManagerMock()
        );
    }

    public function testCreateUser() {
        $user =
            (new ProductOwner())
            ->setUsername('John Doe')
            ->setEmail('john_doe@gmail.com')
        ;
        $this->assertTrue($this->manager->createUser($this->getFormMock(), $user));
    }

    public function testInvalidCreateUser() {
        $user =
            (new ProductOwner())
            ->setUsername('Toto')
            ->setEmail('toto@gmail.com')
        ;
        $this->assertFalse($this->manager->createUser($this->getFormMock(), $user));
    }

    public function testUpdateUser() {
        $this->assertNull($this->manager->updateUser((new ProductOwner())->setUsername('Toto')));
    }

    public function testActivateUserAccount() {
        $token = $this->manager->activateUserAccount('ibfnf5g6sd1f3f');

        $this->assertInstanceOf(UsernamePasswordToken::class, $token);
        $this->assertCount(2, $token->getRoles());
        $this->assertTrue($token->isAuthenticated());
        $this->assertEquals('John Doe', $token->getUsername());
    }

    public function testSendNewActivationLink() {
        $this->assertTrue($this->manager->sendNewActivationLink('valid_email@example.org'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testSendNewActivationLinkWithUnknownEmail() {
        $this->manager->sendNewActivationLink('unknown@example.org');
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedMessage users.activation_link.user_already_enabled
     */
    public function testSendNewActivationLinkWithAlreadyEnabledUser() {
        $this->manager->sendNewActivationLink('already_enabled@example.org');
    }

    public function getEntityManagerMock() {
        $entityManagerMock = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback([$this, 'getRepositoryMock'])
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true)
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('remove')
            ->willReturn(true)
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('flush')
            ->willReturn(true)
        ;
        return $entityManagerMock;
    }

    public function getRepositoryMock() {
        $repositoryMock = $this
            ->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'checkUsername',
                'checkEmail',
                'findOneByActivationLink',
                'findOneByUsernameOrEmail'
            ])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('checkUsername')
            ->willReturnCallback([$this, 'getCheckUsernameMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('checkEmail')
            ->willReturnCallback([$this, 'getCheckEmailMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findOneByActivationLink')
            ->willReturnCallback([$this, 'getUserByActivationLinkMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findOneByUsernameOrEmail')
            ->willReturnCallback([$this, 'getUserMock'])
        ;
        return $repositoryMock;
    }

    public function getUserByActivationLinkMock(ActivationLink $activationLink) {
        return
            (new ProductOwner())
            ->setUsername('John Doe')
            ->setActivationLink($activationLink)
            ->enable(false)
            ->addRole('ROLE_USER')
            ->addRole('ROLE_MEMBER')
        ;
    }

    public function getUserMock($identifier) {
        if($identifier === 'unknown@example.org') {
            return null;
        } elseif($identifier === 'already_enabled@example.org') {
            return
                (new ProductOwner())
                ->enable(true)
            ;
        }
        return
            (new ProductOwner())
            ->enable(false)
        ;
    }

    /**
     * @param string $username
     * @return boolean
     */
    public function getCheckUsernameMock($username) {
        if($username === 'Toto') {
            return false;
        }
        return true;
    }

    /**
     * @param string $email
     * @return boolean
     */
    public function getCheckEmailMock($email) {
        if($email === 'toto@gmail.com') {
            return false;
        }
        return true;
    }

    public function getEncoderMock() {
        $encoderMock = $this
            ->getMockBuilder(UserPasswordEncoder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $encoderMock
            ->expects($this->any())
            ->method('encodePassword')
            ->willReturn(true)
        ;
        return $encoderMock;
    }

    public function getActivationLinkManagerMock() {
        $activationLinkManagerMock = $this
            ->getMockBuilder(ActivationLinkManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $activationLinkManagerMock
            ->expects($this->any())
            ->method('createActivationLink')
            ->willReturn(true)
        ;
        $activationLinkManagerMock
            ->expects($this->any())
            ->method('sendValidationMail')
            ->willReturn(true)
        ;
        $activationLinkManagerMock
            ->expects($this->any())
            ->method('findOneByHash')
            ->willReturnCallback([$this, 'getActivationLinkMock'])
        ;
        return $activationLinkManagerMock;
    }

    public function getActivationLinkMock() {
        return
            (new ActivationLink())
            ->setId(1)
            ->setHash('ibfnf5g6sd1f3f')
            ->setCreatedAt(new \DateTime())
        ;
    }

    public function getFormMock() {
        $formMock = $this
            ->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $formMock
            ->expects($this->any())
            ->method('get')
            ->willReturnSelf()
        ;
        $formMock
            ->expects($this->any())
            ->method('addError')
            ->willReturn(true)
        ;
        return $formMock;
    }

    public function getTranslatorMock() {
        $translatorMock = $this
            ->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $translatorMock
            ->expects($this->any())
            ->method('trans')
            ->willReturnCallback([$this, 'getTranslationMock'])
        ;
        return $translatorMock;
    }

    public function getTranslationMock($translation) {
        $translations = [
            'users.registration.username_already_taken' => 'Ce nom d\utilisateur est déjà utilisé',
            'users.registration.email_already_taken' => 'Cette adresse e-mail est déjà utilisée'
        ];
        return $translations[$translation];
    }
}
