<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author')]
    private Collection $posts;

    #[ORM\OneToOne(mappedBy: 'changed_by', cascade: ['persist', 'remove'])]
    private ?Approval $approval = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column]
    private \DateTimeImmutable $created_at;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, TemporaryUploadedFile>
     */
    #[ORM\OneToMany(targetEntity: TemporaryUploadedFile::class, mappedBy: 'uploader', orphanRemoval: true)]
    private Collection $temporaryUploadedFiles;

    /**
     * @var Collection<int, Approval>
     */
    #[ORM\OneToMany(targetEntity: Approval::class, mappedBy: 'changed_by')]
    private Collection $approvals;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->temporaryUploadedFiles = new ArrayCollection();
        $this->approvals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    public function getApproval(): ?Approval
    {
        return $this->approval;
    }

    public function setApproval(?Approval $approval): static
    {
        // unset the owning side of the relation if necessary
        if ($approval === null && $this->approval !== null) {
            $this->approval->setChangedBy(null);
        }

        // set the owning side of the relation if necessary
        if ($approval !== null && $approval->getChangedBy() !== $this) {
            $approval->setChangedBy($this);
        }

        $this->approval = $approval;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

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

    /**
     * @return Collection<int, TemporaryUploadedFile>
     */
    public function getTemporaryUploadedFiles(): Collection
    {
        return $this->temporaryUploadedFiles;
    }

    public function addTemporaryUploadedFile(TemporaryUploadedFile $temporaryUploadedFile): static
    {
        if (!$this->temporaryUploadedFiles->contains($temporaryUploadedFile)) {
            $this->temporaryUploadedFiles->add($temporaryUploadedFile);
            $temporaryUploadedFile->setUploader($this);
        }

        return $this;
    }

    public function removeTemporaryUploadedFile(TemporaryUploadedFile $temporaryUploadedFile): static
    {
        if ($this->temporaryUploadedFiles->removeElement($temporaryUploadedFile)) {
            // set the owning side to null (unless already changed)
            if ($temporaryUploadedFile->getUploader() === $this) {
                $temporaryUploadedFile->setUploader(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Approval>
     */
    public function getApprovals(): Collection
    {
        return $this->approvals;
    }

    public function addApproval(Approval $approval): static
    {
        if (!$this->approvals->contains($approval)) {
            $this->approvals->add($approval);
            $approval->setChangedBy($this);
        }

        return $this;
    }

    public function removeApproval(Approval $approval): static
    {
        if ($this->approvals->removeElement($approval)) {
            // set the owning side to null (unless already changed)
            if ($approval->getChangedBy() === $this) {
                $approval->setChangedBy(null);
            }
        }

        return $this;
    }
}
