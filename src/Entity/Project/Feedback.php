<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\FeedbackRepository")
 * @ORM\Table(name="project__feedbacks")
 * @ORM\HasLifecycleCallbacks()
 */
class Feedback extends Job
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     **/
    protected $author;

    const STATUS_OPEN = 0;
    const STATUS_TO_DO = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_TO_VALIDATE = 3;
    const STATUS_DONE = 4;
    const STATUS_CLOSED = 5;

    public function setAuthor(UserInterface $author): Feedback
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): UserInterface
    {
        return $this->author;
    }

    public function getType(): string
    {
        return self::TYPE_FEEDBACK;
    }
}
