<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    private $clientProfileDirectory;
    private $userProfileDirectory;
    private $productDirectory;
    private $slugger;

    public function __construct($clientProfileDirectory, $productDirectory,$userProfileDirectory, SluggerInterface $slugger)
    {

        $this->clientProfileDirectory = $clientProfileDirectory;
        $this->userProfileDirectory = $userProfileDirectory;
        $this->productDirectory = $productDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, string $type)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            switch ($type) {
                case 'client_profile':
                    $file->move($this->getProfileDirectory(), $fileName);
                    break;
                case 'user_profile':
                    $file->move($this->getUserProfileDirectory(), $fileName);
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

    public function getProfileDirectory()
    {
        return $this->clientProfileDirectory;
    }

    public function getUserProfileDirectory()
    {
        return $this->userProfileDirectory;
    }

    public function getProductDirectory()
    {
        return $this->productDirectory;
    }
}
