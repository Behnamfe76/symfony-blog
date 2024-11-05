<?php

namespace App\Traits;


use App\Entity\Media;
use App\Entity\TemporaryUploadedFile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

trait UploadFileHandler
{
    public function uploadTemporaryFile(
        UploadedFile           $file,
        string                 $project_dir,
        EntityManagerInterface $entityManager,
                               $user
    ): \Throwable|\Exception|TemporaryUploadedFile
    {
        try {
            $folderName = uniqid();
            $destination = $project_dir . '/var/uploads/temp/' . $folderName;
            $fileName = $file->getClientOriginalName();
            $file->move($destination, $fileName);

            $tempFile = new TemporaryUploadedFile();
            $tempFile->setUploader($user);
            $tempFile->setFileName($fileName);
            $tempFile->setFolderName($folderName);
            $entityManager->persist($tempFile);

            $entityManager->flush();

            return $tempFile;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function moveTemporaryFile(
        EntityManagerInterface $entityManager,
        TemporaryUploadedFile  $tempFile,
        string                 $project_dir,
        User                   $user,
        string                 $targetModel,
        string                 $targetFolder
    )
    {
        $fileSystem = new Filesystem();
        $folderName = $tempFile->getFolderName();
        $fileName = $tempFile->getFileName();
        $tempPath = $project_dir . '/var/uploads/temp/' . $folderName . '/';

        if ($fileSystem->exists($tempPath . $fileName)) {
            $destination = $project_dir . '/public/uploads/' . $targetFolder . '/' . $folderName . '/' . $user->getId();
            $publicPath = $_ENV['APP_URL'] . '/uploads/' . $targetFolder . '/' . $folderName . '/' . $user->getId();
            if (!$fileSystem->exists($destination)) {
                $fileSystem->mkdir($destination, 0755);
            }
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $fileExtension;

            $fileSystem->rename($tempPath . $fileName, $destination . '/' . $newFileName);

            $media = new Media();
            $media->setModel($targetModel);
            $media->setFileName($newFileName);
            $media->setUuid(Uuid::v1());
            $media->setLink($publicPath . '/' . $newFileName);
            // Get file path
            $filePath = $destination . '/' . $newFileName;

            // Set MIME type
            $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $fileInfo->file($filePath);
            $media->setMimeType($mimeType);

            // Set file size
            $fileSize = filesize($filePath);
            $media->setSize($fileSize);

            $entityManager->persist($media);


            if (is_dir($tempPath) && count(scandir($tempPath)) === 2) {
                $fileSystem->remove($tempPath);
            }

            return $media;
        }
    }
}