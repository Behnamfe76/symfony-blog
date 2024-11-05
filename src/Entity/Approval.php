<?php

namespace App\Entity;

use App\Enums\PostStatusEnum;
use App\Repository\ApprovalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApprovalRepository::class)]
#[ORM\Table(name: 'approvals')]
class Approval
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $approved_at = null;

    #[ORM\Column(length: 255)]
    private PostStatusEnum $changed_to = PostStatusEnum::PENDING;


    #[ORM\OneToOne(inversedBy: 'approval', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\Column]
    private \DateTimeImmutable $created_at;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'approvals')]
    private ?User $changed_by = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApprovedAt(): ?\DateTimeImmutable
    {
        return $this->approved_at;
    }

    public function setApprovedAt(\DateTimeImmutable $approved_at): static
    {
        $this->approved_at = $approved_at;

        return $this;
    }

    public function getChangedTo(): PostStatusEnum
    {
        return $this->changed_to;
    }

    public function setChangedTo(PostStatusEnum $changed_to): static
    {
        $this->changed_to = $changed_to;

        return $this;
    }
    

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): static
    {
        $this->post = $post;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getChangedBy(): ?User
    {
        return $this->changed_by;
    }

    public function setChangedBy(?User $changed_by): static
    {
        $this->changed_by = $changed_by;

        return $this;
    }
}
