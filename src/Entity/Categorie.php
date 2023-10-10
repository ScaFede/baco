<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\ManyToMany(targetEntity: CompetenzeBis::class, inversedBy: 'categorieRelation')]
    private Collection $competenzeRelation;

    public function __construct()
    {
        $this->competenzeRelation = new ArrayCollection();
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
     * @return Collection<int, CompetenzeBis>
     */
    public function getCompetenzeRelation(): Collection
    {
        return $this->competenzeRelation;
    }

    public function addCompetenzeRelation(CompetenzeBis $competenzeRelation): static
    {
        if (!$this->competenzeRelation->contains($competenzeRelation)) {
            $this->competenzeRelation->add($competenzeRelation);
        }

        return $this;
    }

    public function removeCompetenzeRelation(CompetenzeBis $competenzeRelation): static
    {
        $this->competenzeRelation->removeElement($competenzeRelation);

        return $this;
    }

        public function __toString() {
        return $this->getNome(); // Dove getNome() ritorna il nome dell'oggetto
    }
}
