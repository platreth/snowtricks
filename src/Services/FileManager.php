<?php
// src/Service/FileUpload.php
namespace App\Service;

use App\Entity\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class FileManager
{
    private $params;

    /**
     * FileManager constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    private function getTargetDirectory() {
        return $this->params->get("picture_directory");
    }

    /**
     * @param UploadedFile $file
     * @param string $title
     * @param string $path
     * @param string $type
     * @return File
     * @throws \Exception
     */
    public function upload(UploadedFile $file, string $title, string $path, $type)
    {
        $newFile = new File();
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFile->setOriginalName($originalFilename);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $newFile->setName($fileName)
            ->setTitle($title)
            ->setSizeOrigin($file->getSize())
            ->setDateCreated(new \DateTime())
            ->setType($type);
        try {
            $file->move($this->getTargetDirectory() . '/' . $path, $fileName);
        }
        catch (FileException $e) {
        }
        return $newFile;
    }

    /**
     * @param File $file
     */
    public function deleteFile(File $file) {
        $fileSystem = new Filesystem();
        try {
            $fileSystem->remove($this->getTargetDirectory() . $file->getName());
        }
        catch (IOExceptionInterface $exception) {
        }
    }
}