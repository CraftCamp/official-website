<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Picture as PictureModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="pictures")
 * @ORM\HasLifecycleCallbacks
 */
class Picture extends PictureModel
{
    const UPLOAD_DIR = 'images/uploads';
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $path;

    public function setId(int $id): Picture
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->path = "{$this->name}.{$this->file->guessExtension()}";
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->file->move(
            $this->getUploadRootDir(),
            $this->path
        );
        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }
}