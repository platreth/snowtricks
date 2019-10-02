<?php

// src/Service/FileUpload.php
namespace App\Services;

use App\Entity\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    private function getTargetDirectory() {
        return $this->params->get("picture_directory");
    }

    public function upload(UploadedFile $file, string $title)
    {
        $newFile = new File();

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFile->setOriginalName($originalFilename);

        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $newFile->setName($fileName)
                     ->setTitle($title)
                     ->setSizeOrigin($file->getSize())
                     ->setDateCreated(new \DateTime());

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        }
        catch (FileException $e) {

        }

        return $newFile;
    }

    public function deleteFile(File $file) {
        $fileSystem = new Filesystem();
        try {
            $fileSystem->remove($this->getTargetDirectory() . $file->getName());
        }
        catch (IOExceptionInterface $exception) {

        }
    }

}