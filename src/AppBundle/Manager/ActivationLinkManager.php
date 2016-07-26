<?php
namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User\{User, ActivationLink};
use AppBundle\Utils\Mailer;

class ActivationLinkManager {
    /** @var \Doctrine\ORM\EntityManager **/
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param \AppBundle\Utils\Mailer $mailer
     */
    public function __construct(EntityManager $em, Mailer $mailer) {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    /**
     * @param \AppBundle\Entity\User\User $user
     */
    public function createActivationLink(User $user) {
        $activationLink =
            (new ActivationLink())
            ->setHash(md5(uniqid(null, true)))
        ;
        $user->setActivationLink($activationLink);
        $this->em->persist($activationLink);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param \AppBundle\Entity\User\User $user
     * @return bool
     */
    public function sendValidationMail(User $user): bool {
        $nbRecipients = $this->mailer->sendTo($user->getEmail(), 'users.registration.mails.confirmation', [
            '%username%' => $user->getUsername(),
            '%activation_link%' => $user->getActivationLink()->getHash()
        ], 'Personal');
        return $nbRecipients > 0;
    }

    /**
     * @param string $hash
     * @return \AppBundle\Entity\User\ActivationLink
     */
    public function findOneByHash(string $hash) {
        return $this->em->getRepository(ActivationLink::class)->findOneByHash($hash);
    }
}
