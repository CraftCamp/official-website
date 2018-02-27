<?php
namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User\{User, ActivationLink};
use App\Utils\Mailer;

class ActivationLinkManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var Mailer **/
    protected $mailer;

    /**
     * @param EntityManagerInterface $em
     * @param Mailer $mailer
     */
    public function __construct(EntityManagerInterface $em, Mailer $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    /**
     * @param \User $user
     */
    public function createActivationLink(User $user)
    {
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
     * @param User $user
     * @return bool
     */
    public function sendValidationMail(User $user): bool
    {
        $nbRecipients = $this->mailer->sendTo($user->getEmail(), 'users.registration.mails.confirmation', [
            '%username%' => $user->getUsername(),
            '%activation_link%' => $user->getActivationLink()->getHash()
        ], 'Personal');
        return $nbRecipients > 0;
    }

    /**
     * @param string $hash
     * @return ActivationLink
     */
    public function findOneByHash(string $hash)
    {
        return $this->em->getRepository(ActivationLink::class)->findOneByHash($hash);
    }
}
