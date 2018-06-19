<?php

namespace App\Manager\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;

use App\Entity\User\Notification;

use App\Entity\User\Member;

class NotificationManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var TranslatorInterface **/
    protected $translator;
    
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }
    
    public function notifyAllMembers(string $content, array $parameters = [])
    {
        $this->notify($this->em->getRepository(Member::class)->findAll(), $content, $parameters);
    }
    
    protected function notify(array $users, string $content, array $parameters = [])
    {
        $notification =
            (new Notification())
            ->setContent($this->translator->trans($content, $parameters))
        ;
        foreach ($users as $user) {
            $n = clone $notification;
            $n->setUser($user);
            $this->em->persist($n);
        }
        $this->em->flush();
    }
}