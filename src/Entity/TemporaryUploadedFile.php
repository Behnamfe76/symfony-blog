<?php

namespace App\Entity;

use App\Repository\TemporaryUploadedFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemporaryUploadedFileRepository::class)]
class TemporaryUploadedFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public ?string $folder_name = null;

    #[ORM\Column(length: 255)]
    public ?string $file_name = null;

    #[ORM\ManyToOne(inversedBy: 'temporaryUploadedFiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $uploader = null;

    #[ORM\Column]
    private \DateTimeImmutable $created_at;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFolderName(): ?string
    {
        return $this->folder_name;
    }

    public function setFolderName(string $folder_name): static
    {
        $this->folder_name = $folder_name;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getUploader(): ?User
    {
        return $this->uploader;
    }

    public function setUploader(?User $uploader): static
    {
        $this->uploader = $uploader;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
