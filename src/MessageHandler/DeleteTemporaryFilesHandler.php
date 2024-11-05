<?php

namespace App\MessageHandler;

use App\Entity\TemporaryUploadedFile;
use App\Message\DeleteTemporaryFiles;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class DeleteTemporaryFilesHandler
{
    private Filesystem $filesystem;
    private EntityManagerInterface  $entityManager;

    public function __construct(LoggerInterface $logger, Filesystem $filesystem, EntityManagerInterface  $entityManager)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    public function __invoke(DeleteTemporaryFiles $message): void
    {
        $tempFileId = $message->getTempFileId();
        $project_dir = $message->getProjectDir();

        $tempFile = $this->entityManager->getRepository(TemporaryUploadedFile::class)->find($tempFileId);

        try {
            $folder = $tempFile->folder_name;
            $fileName = $tempFile->file_name;
            $path = sprintf($project_dir . '\var\uploads\temp\\' . $folder . '\\');

            if ($this->filesystem->exists($path . $fileName)) {
                $this->filesystem->remove($path . $fileName);
                $this->entityManager->remove($tempFile);
                $this->entityManager->flush();

                $this->logger->info('file deleted: ' . $path . $fileName);
            }


            // Remove the directory if empty
            if (is_dir($path) && count(scandir($path)) === 2) {
                $this->filesystem->remove($path);
            }
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
        }
    }
}
