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
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $path;
    /** @var File */
    protected $file;
    /** @var resource **/
    protected $temp;

    public function setId(int $id): Picture
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setName(string $name): Picture
    {
        $this->name = $name;
        
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
    
    public function setPath(string $path): Picture
    {
        $this->path = $path;
        
        return $this;
    }
    
    public function getPath(): string
    {
        return $this->path;
    }
    
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
    
    public function getAbsolutePath()
    {
        return
            (null === $this->path)
            ? null
            : $this->getUploadRootDir().'/'.$this->id.'.'.$this->path
        ;
    }
    
    public function getWebPath()
    {
        return 
            (null === $this->path)
            ? null
            : $this->getUploadDir().'/'.$this->path
        ;
    }
    
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
    
    public function jsonSerialize(): array
    {
        return [
            'path' => $this->getWebPath()
        ];
    }
    
}