<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    private $targetDirectory;

    public function __construct()
    {
        $this->targetDirectory = 'uploads/';
    }

    public function upload(UploadedFile $file, $directory )
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move( $this->targetDirectory.$directory, $fileName);
        } catch (FileException $e) {
            $this->logger->error("Erreur lors de l'import d'image: " . $e->getMessage());
            throw new FileException("Erreur import d'image");
        }

        return $fileName;
    }

}