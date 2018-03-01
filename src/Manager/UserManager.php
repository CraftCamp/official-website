<?php
namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\User\User;
use App\Entity\User\ProductOwner;
use App\Entity\User\Member;
use App\Entity\User\BetaTester;
use App\Entity\Organization;
use App\Manager\ActivationLinkManager;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var UserPasswordEncoderInterface **/
    protected $encoder;
    /** @var TranslatorInterface **/
    protected $translator;
    /** @var ActivationLinkManager **/
    protected $activationLinkManager;

    /**
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param TranslatorInterface $translator
     * @param ActivationLinkManager
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, TranslatorInterface $translator, ActivationLinkManager $activationLinkManager)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->translator = $translator;
        $this->activationLinkManager = $activationLinkManager;
    }

    /**
     * @param string $identifier
     * @return \App\Entity\User\User
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
                if ($organization !== null) {
                    $user->setOrganization($organization);
                }
				break;
			case User::TYPE_MEMBER:
				$user = new Member();
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
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param string $activationHash
     * @throws \InvalidArgumentException
     * @return UsernamePasswordToken
     */
    public function activateUserAccount(string $activationHash): UsernamePasswordToken
    {
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

        return $user;
    }

    /**
     * @param string $email
     * @throws UsernameNotFoundException
     * @throws BadRequestHttpException
     * @return bool
     */
    public function sendNewActivationLink($email)
    {
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
    public function checkUsername(string $username): bool
    {
        return $this->em->getRepository(User::class)->checkUsername($username);
    }

    /**
     * @param string $email
     * @return bool
     */
    public function checkEmail(string $email): bool
    {
        return $this->em->getRepository(User::class)->checkEmail($email);
    }
}
