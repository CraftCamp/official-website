<?php

namespace AppBundle\Entity;

use Developtech\AgilityBundle\Model\FeatureModel;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class UserStory extends FeatureModel {
    /**
     * @var UserInterface
     *
     * The user story responsible
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    protected $developer;

    /**
     * @var Project
     *
     * The project class you extended
     * It is not mandatory to create a bi-directional relationship between projects and features
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="features")
     */
    protected $project;
}
