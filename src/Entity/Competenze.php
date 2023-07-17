<?php

namespace App\Entity;

use App\Repository\CompetenzeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetenzeRepository::class)]
class Competenze
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titolo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descrizione = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(nullable: true)]
    private \DateTimeImmutable $createAt;

    #[ORM\ManyToOne(inversedBy: 'competenzeRel')]
    private ?User $userRel = null;


    public function __construct(){
           $this->createAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitolo(): ?string
    {
        return $this->titolo;
    }

    public function setTitolo(string $titolo): static
    {
        $this->titolo = $titolo;

        return $this;
    }

    public function getDescrizione(): ?string
    {
        return $this->descrizione;
    }

    public function setDescrizione(string $descrizione): static
    {
        $this->descrizione = $descrizione;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }


    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUserRel(): ?User
    {
        return $this->userRel;
    }

    public function setUserRel(?User $userRel): static
    {
        $this->userRel = $userRel;

        return $this;
    }

    public function __toString()
    {
        return $this->titolo;
    }
}
