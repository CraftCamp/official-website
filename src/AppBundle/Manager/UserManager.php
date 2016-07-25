<?php
namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use AppBundle\Entity\User\User;
use Doctrine\ORM\ORMException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use AppBundle\Manager\ActivationLinkManager;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\{Form, FormError};
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
     * @param \Symfony\Component\Form\Form $form
     * @param \AppBundle\Entity\User\User $user
     * @return bool
     */
    public function createUser(Form $form, User &$user): bool {
        $return = false;
        if(!$this->checkUsername($user->getUsername())) {
            $return = true;
            $form->get('username')->addError(new FormError(
                $this->translator->trans('users.registration.username_already_taken'))
            );
        }
        if(!$this->checkEmail($user->getEmail())) {
            $return = true;
            $form->get('email')->addError(new FormError(
                $this->translator->trans('users.registration.email_already_taken'))
            );
        }
        if($return) {
            return false;
        }
        $user->setSalt(uniqid('', true));
        $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->addRole('ROLE_USER');
        $user->enable(false);
        $user->setIsLocked(false);

        $this->em->persist($user);
        $this->em->flush();

        $this->activationLinkManager->createActivationLink($user);
        $this->activationLinkManager->sendValidationMail($user);

        return true;
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
