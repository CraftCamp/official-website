<?php

namespace AppBundle\Entity;

use Developtech\AgilityBundle\Model\ProjectModel;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Project extends ProjectModel {
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\ProductOwner")
     */
    protected $productOwner;

    /**
     * @var ArrayCollection
     *
     * The feature class you extended
     * It is not mandatory to create a bi-directional relationship between projects and features
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserStory", mappedBy="project")
     */
    protected $features;

    /**
     * @var ArrayCollection
     *
     * The feedback class you extended
     * It is not mandatory to create a bi-directional relationship between projects and feedbacks
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Feedback", mappedBy="project")
     */
    protected $feedbacks;
}
