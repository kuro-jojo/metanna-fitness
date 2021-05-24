<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader{
    private $profilDirectory;
    private $productDirectory;
    private $slugger;

    public function __construct($profilDirectory,$productDirectory, SluggerInterface $slugger)
    {
    
        $this->profilDirectory = $profilDirectory;
        $this->productDirectory = $productDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file,string $type)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            switch ($type) {
                case 'profil':
                    $file->move($this->getProfilDirectory(), $fileName);      
                    break;
                case 'product';
                $file->move($this->getProductDirectory(), $fileName);      
                    
                default:
                    # code...
                    break;
            }
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getProfilDirectory()
    {
        return $this->profilDirectory;
    }

    public function getProductDirectory()
    {
        return $this->productDirectory;
    }

}