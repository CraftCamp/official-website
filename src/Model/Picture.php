<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class Picture implements \JsonSerializable
{
    const UPLOAD_DIR = 'images/uploads';
    
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $path;
    /** @var File */
    protected $file;
    /** @var resource **/
    protected $temp;
    
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
        if ($this->file !== null && is_file($this->getAbsolutePath())) {
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
    
    public function getAbsolutePath(): string
    {
        return $this->getUploadRootDir() . '/' . $this->path;
    }
    
    public function getWebPath(): string
    {
        return $this->getUploadDir() . '/' . $this->path;
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