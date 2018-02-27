<?php
namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use AppBundle\Entity\User\User;
use AppBundle\Entity\User\ProductOwner;
use AppBundle\Entity\User\Developer;
use AppBundle\Entity\User\BetaTester;
use AppBundle\Entity\Organization;
use Doctrine\ORM\ORMException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use AppBundle\Manager\ActivationLinkManager;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserManager {
    /** @var \Doctrine\ORM\EntityManager **/
    protected $em;
    /** @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoder **/
    protected $encoder;
    /** @var \Symfony\Component\Translation\TranslatorInterface **/
    protected $translator;
    /** @var \AppBundle\Manager\ActivationLinkManager **/
    protected $activationLinkManager;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoder $encoder
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \AppBundle\Manager\ActivationLinkManager
     */
    public function __construct(EntityManager $em, UserPasswordEncoder $encoder, TranslatorInterface $translator, ActivationLinkManager $activationLinkManager) {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->translator = $translator;
        $this->activationLinkManager = $activationLinkManager;
    }

    /**
     * @param string $identifier
     * @return \AppBundle\Entity\User\User
     */
    public function findUserByUsernameOrEmail($identifier) {
        return $this
            ->em
            ->getRepository(User::class)
            ->findOneByUsernameOrEmail($identifier)
        ;
    }

    /**
     * @param array $data
     * @param string $type
	 * @param Organization $organization
     * @return User
     */
    public function createUser($data, $type, Organization $organization = null): User
	{
        if (empty($data['username']) || !$this->checkUsername($data['username'])) {
            throw new BadRequestHttpException('users.invalid_username');
        }
        if (empty($data['email']) || !$this->checkEmail($data['email'])) {
            throw new BadRequestHttpException('users.invalid_email');
        }
		if (empty($data['password']) || empty($data['password_confirmation'])) {
            throw new BadRequestHttpException('users.invalid_password');
		}
		if ($data['password'] !== $data['password_confirmation']) {
			throw new BadRequestHttpException('users.password_mismatch');
		}
		switch($type) {
			case User::TYPE_PRODUCT_OWNER: 
				$user = new ProductOwner();
				$user->setOrganization($organization);
				break;
			case User::TYPE_DEVELOPER:
				$user = new Developer();
				break;
			case User::TYPE_BETA_TESTER:
				$user = new BetaTester();
				break;
			default:
				throw new BadRequestHttpException('users.invalid_type');
		}
		$user->setUsername($data['username']);
		$user->setEmail($data['email']);
        $user->setSalt(uniqid('', true));
        $password = $this->encoder->encodePassword($user, $data['password']);
        $user->setPassword($password);
        $user->addRole('ROLE_USER');
        $user->enable(false);
        $user->setIsLocked(false);

        $this->em->persist($user);
        $this->em->flush();

        $this->activationLinkManager->createActivationLink($user);
        $this->activationLinkManager->sendValidationMail($user);

        return $user;
    }

    /**
     * @param \AppBundle\Entity\User\User $user
     */
    public function updateUser(User $user) {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param string $activationHash
     * @throws \InvalidArgumentException
     * @return \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken
     */
    public function activateUserAccount(string $activationHash): UsernamePasswordToken {
        $activationLink = $this->activationLinkManager->findOneByHash($activationHash);
        if($activationLink === null) {
            throw new \InvalidArgumentException('not_found');
        }
        $datetime = new \DateTime();
        $datetime->setTimestamp((time() - 86400));
        if($activationLink->getCreatedAt() < $datetime) {
            throw new \InvalidArgumentException('invalid');
        }
        $user = $this->em->getRepository(User::class)->findOneByActivationLink($activationLink);
        if($user === null) {
            $this->em->remove($activationLink);
            $this->em->flush();
            throw new \InvalidArgumentException('unrelated');
        }

        $user->enable(true);
        $user->setActivationLink(null);
        $this->em->persist($user);
        $this->em->remove($activationLink);
        $this->em->flush();

        return new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    }

    /**
     * @param string $email
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @return bool
     */
    public function sendNewActivationLink($email) {
        if(($user = $this->findUserByUsernameOrEmail($email)) === null) {
            throw new UsernameNotFoundException();
        }
        if($user->isEnabled()) {
            throw new BadRequestHttpException('users.activation_link.user_already_enabled');
        }
        if(($activationLink = $user->getActivationLink()) !== null) {
            $user->setActivationLink(null);
            $this->em->persist($user);
            $this->em->remove($activationLink);
            $this->em->flush();
        }
        $this->activationLinkManager->createActivationLink($user);
        $this->activationLinkManager->sendValidationMail($user);
        return true;
    }

    /**
     * @param string $username
     * @return bool
     */
    public function checkUsername(string $username): bool {
        return $this->em->getRepository(User::class)->checkUsername($username);
    }

    /**
     * @param string $email
     * @return bool
     */
    public function checkEmail(string $email): bool {
        return $this->em->getRepository(User::class)->checkEmail($email);
    }
}
