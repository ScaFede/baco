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

#[ORM\Entity(repositoryClass: UserRepository::class)]
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

    public function __construct()
    {
        $this->competenzeRel = new ArrayCollection();
        $this->createAt = new \DateTimeImmutable();
        $this->CompetenzeBisRel = new ArrayCollection();
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


}
