<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity()
 * @ORM\Table(name="pictures")
 * @ORM\HasLifecycleCallbacks
 */
class Picture implements \JsonSerializable
{
    const UPLOAD_DIR = 'images/uploads';
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $path;
    /**
     * @var UploadedFile
     */
    protected $file;
    /** @var resource **/
    protected $temp;

    /**
     * @param int $id
     * @return \App\Model\PictureModel
     */
    public function setId(int $id): Picture
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param string $name
     * @return \App\Model\PictureModel
     */
    public function setName(string $name): Picture
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $path
     * @return \App\Model\PictureModel
     */
    public function setPath(string $path): Picture
    {
        $this->path = $path;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     * @return Picture
     */
    public function setFile(UploadedFile $file = null): Picture
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
            $this->path = null;
        }
        return $this;
    }
    
    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
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
    
    /**
     * @return string
     */
    public function getAbsolutePath()
    {
        return
            (null === $this->path)
            ? null
            : $this->getUploadRootDir().'/'.$this->id.'.'.$this->path
        ;
    }
    
    /**
     * @return string
     */
    public function getWebPath()
    {
        return 
            (null === $this->path)
            ? null
            : $this->getUploadDir().'/'.$this->path
        ;
    }
    
    /**
     * @return string
     */
    public function getUploadDir(): string
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return '/' . self::UPLOAD_DIR;
    }
    
    public function getUploadRootDir(): string
    {
        return __DIR__ . '/../../public/' . self::UPLOAD_DIR;
    }
    
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'path' => $this->getWebPath()
        ];
    }
    
}