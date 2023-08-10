<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]

#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nickname = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private \DateTimeImmutable $createAt;

    #[ORM\OneToMany(mappedBy: 'userRel', targetEntity: Competenze::class)]
    private Collection $competenzeRel;

    #[ORM\ManyToMany(targetEntity: CompetenzeBis::class, mappedBy: 'UserRelation')]
    private Collection $CompetenzeBisRel;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\ManyToMany(targetEntity: Scambi::class, mappedBy: 'userTarget')]
    private Collection $ScambiUser;

    #[ORM\OneToMany(mappedBy: 'userSender', targetEntity: Scambi::class)]
    private Collection $scambiUserSender;

    #[ORM\Column(nullable: true)]
    private ?int $scambiConclusi = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
  //  #[Vich\UploadableField(mapping: 'bacouploader', fileNameProperty: 'imageName', size: 'imageSize')]
  //  private ?File $imageFile = null;

    public function __construct()
    {
        $this->competenzeRel = new ArrayCollection();
        $this->createAt = new \DateTimeImmutable();
        $this->CompetenzeBisRel = new ArrayCollection();
        $this->ScambiUser = new ArrayCollection();
        $this->scambiUserSender = new ArrayCollection();
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return Collection<int, Competenze>
     */
    public function getCompetenzeRel(): Collection
    {
        return $this->competenzeRel;
    }

    public function addCompetenzeRel(Competenze $competenzeRel): static
    {
        if (!$this->competenzeRel->contains($competenzeRel)) {
            $this->competenzeRel->add($competenzeRel);
            $competenzeRel->setUserRel($this);
        }

        return $this;
    }

    public function removeCompetenzeRel(Competenze $competenzeRel): static
    {
        if ($this->competenzeRel->removeElement($competenzeRel)) {
            // set the owning side to null (unless already changed)
            if ($competenzeRel->getUserRel() === $this) {
                $competenzeRel->setUserRel(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nickname;
    }

    /**
     * @return Collection<int, CompetenzeBis>
     */
    public function getCompetenzeBisRel(): Collection
    {
        return $this->CompetenzeBisRel;
    }

    public function addCompetenzeBisRel(CompetenzeBis $competenzeBisRel): static
    {
        if (!$this->CompetenzeBisRel->contains($competenzeBisRel)) {
            $this->CompetenzeBisRel->add($competenzeBisRel);
            $competenzeBisRel->addUserRelation($this);
        }

        return $this;
    }

    public function removeCompetenzeBisRel(CompetenzeBis $competenzeBisRel): static
    {
        if ($this->CompetenzeBisRel->removeElement($competenzeBisRel)) {
            $competenzeBisRel->removeUserRelation($this);
        }

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }

    public function setImageFile(?string $imageFile): static
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }




      public function getAvatarUrl(): ?string
      {
          if (!$this->avatar) {
              return null;
          }

          if (strpos($this->avatar, '/') !== false) {
              return $this->avatar;
          }

          return sprintf('/uploads/avatars/%s', $this->avatar);
      }

      /**
       * @return Collection<int, Scambi>
       */
      public function getScambiUser(): Collection
      {
          return $this->ScambiUser;
      }

      public function addScambiUser(Scambi $scambiUser): static
      {
          if (!$this->ScambiUser->contains($scambiUser)) {
              $this->ScambiUser->add($scambiUser);
              $scambiUser->addUserTarget($this);
          }

          return $this;
      }

      public function removeScambiUser(Scambi $scambiUser): static
      {
          if ($this->ScambiUser->removeElement($scambiUser)) {
              $scambiUser->removeUserTarget($this);
          }

          return $this;
      }

      /**
       * @return Collection<int, Scambi>
       */
      public function getScambiUserSender(): Collection
      {
          return $this->scambiUserSender;
      }

      public function addScambiUserSender(Scambi $scambiUserSender): static
      {
          if (!$this->scambiUserSender->contains($scambiUserSender)) {
              $this->scambiUserSender->add($scambiUserSender);
              $scambiUserSender->setUserSender($this);
          }

          return $this;
      }

      public function removeScambiUserSender(Scambi $scambiUserSender): static
      {
          if ($this->scambiUserSender->removeElement($scambiUserSender)) {
              // set the owning side to null (unless already changed)
              if ($scambiUserSender->getUserSender() === $this) {
                  $scambiUserSender->setUserSender(null);
              }
          }

          return $this;
      }

      public function getScambiConclusi(): ?int
      {
          return $this->scambiConclusi;
      }

      public function setScambiConclusi(?int $scambiConclusi): static
      {
          $this->scambiConclusi = $scambiConclusi;

          return $this;
      }







}
