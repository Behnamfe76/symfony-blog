<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column]
    private \DateTimeImmutable $published_at;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\OneToOne(mappedBy: 'post', cascade: ['persist', 'remove'])]
    private ?Approval $approval = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostType $postType = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostCategory $postCategory = null;

    public function __construct()
    {
        $this->published_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeImmutable $published_at): static
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getApproval(): ?Approval
    {
        return $this->approval;
    }

    public function setApproval(Approval $approval): static
    {
        // set the owning side of the relation if necessary
        if ($approval->getPost() !== $this) {
            $approval->setPost($this);
        }

        $this->approval = $approval;

        return $this;
    }

    public function getPostType(): ?PostType
    {
        return $this->postType;
    }

    public function setPostType(?PostType $postType): static
    {
        $this->postType = $postType;

        return $this;
    }

    public function getPostCategory(): ?PostCategory
    {
        return $this->postCategory;
    }

    public function setPostCategory(?PostCategory $postCategory): static
    {
        $this->postCategory = $postCategory;

        return $this;
    }
}
