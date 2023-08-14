<?php

namespace App\Entity;

use App\Repository\CittaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CittaRepository::class)]
class Citta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\OneToMany(mappedBy: 'cittaRel', targetEntity: User::class)]
    private Collection $userRel;

    public function __construct()
    {
        $this->userRel = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserRel(): Collection
    {
        return $this->userRel;
    }

    public function addUserRel(User $userRel): static
    {
        if (!$this->userRel->contains($userRel)) {
            $this->userRel->add($userRel);
            $userRel->setCittaRel($this);
        }

        return $this;
    }

    public function removeUserRel(User $userRel): static
    {
        if ($this->userRel->removeElement($userRel)) {
            // set the owning side to null (unless already changed)
            if ($userRel->getCittaRel() === $this) {
                $userRel->setCittaRel(null);
            }
        }

        return $this;
    }


    public function __toString(): string
    {
        return $this->nome; 
    }
}
