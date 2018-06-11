<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\FeatureRepository")
 * @ORM\Table(name="project__features")
 * @ORM\HasLifecycleCallbacks()
 */
class Feature extends Job
{
    /**
     * @ORM\Column(type="string", length=15)
     */
    protected $featureType;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $productOwnerValue;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $userValue;

    const FEATURE_TYPE_PRODUCT_OWNER = 'product-owner';
    const FEATURE_TYPE_USER = 'user';

    const STATUS_TO_SPECIFY = 0;
    const STATUS_TO_VALORIZE = 1;
    const STATUS_READY = 2;
    const STATUS_TO_DO = 3;
    const STATUS_IN_PROGRESS = 4;
    const STATUS_TO_VALIDATE = 5;
    const STATUS_TO_DEPLOY = 6;

    public function getType(): string
    {
        return self::TYPE_FEATURE;
    }

    public function setFeatureType(string $featureType): Feature
    {
        $this->featureType = $featureType;

        return $this;
    }

    public function getFeatureType(): string
    {
        return $this->featureType;
    }

    public function setProductOwnerValue(int $productOwnerValue): Feature
    {
        $this->productOwnerValue = $productOwnerValue;

        return $this;
    }

    public function getProductOwnerValue(): int
    {
        return $this->productOwnerValue;
    }

    public function setUserValue(int $userValue): Feature
    {
        $this->userValue = $userValue;

        return $this;
    }

    public function getUserValue(): int
    {
        return $this->userValue;
    }
}
