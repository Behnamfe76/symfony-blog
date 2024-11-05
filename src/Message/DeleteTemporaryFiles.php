<?php

namespace App\Message;

use App\Entity\TemporaryUploadedFile;

final class DeleteTemporaryFiles
{
    public TemporaryUploadedFile $tempFile;
    public string $project_dir;

    public function __construct(TemporaryUploadedFile $tempFile, string $project_dir)
    {
        $this->tempFile = $tempFile;
        $this->project_dir = $project_dir;
    }

    public function getTempFileId(): int
    {
        return $this->tempFile->getId();
    }

    public function getProjectDir(): string
    {
        return $this->project_dir;
    }
}
