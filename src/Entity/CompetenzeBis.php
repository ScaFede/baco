<?php

namespace App\Entity;

use App\Repository\CompetenzeBisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetenzeBisRepository::class)]
class CompetenzeBis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Titolo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Descrizione = null;

    #[ORM\Column(nullable: true)]
    private ?bool $StatusActive = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreateAt = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'CompetenzeBisRel')]
    private Collection $UserRelation;

    public function __construct()
    {
        $this->UserRelation = new ArrayCollection();
         $this->CreateAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitolo(): ?string
    {
        return $this->Titolo;
    }

    public function setTitolo(string $Titolo): static
    {
        $this->Titolo = $Titolo;

        return $this;
    }

    public function getDescrizione(): ?string
    {
        return $this->Descrizione;
    }

    public function setDescrizione(string $Descrizione): static
    {
        $this->Descrizione = $Descrizione;

        return $this;
    }

    public function isStatusActive(): ?bool
    {
        return $this->StatusActive;
    }

    public function setStatusActive(?bool $StatusActive): static
    {
        $this->StatusActive = $StatusActive;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->CreateAt;
    }

    public function setCreateAt(\DateTimeImmutable $CreateAt): static
    {
        $this->CreateAt = $CreateAt;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserRelation(): Collection
    {
        return $this->UserRelation;
    }

    public function addUserRelation(User $userRelation): static
    {
        if (!$this->UserRelation->contains($userRelation)) {
            $this->UserRelation->add($userRelation);
        }

        return $this;
    }

    public function removeUserRelation(User $userRelation): static
    {
        $this->UserRelation->removeElement($userRelation);

        return $this;
    }

    public function __toString() {
        return $this->Titolo;
    }
}
