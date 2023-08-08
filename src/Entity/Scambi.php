<?php

namespace App\Entity;

use App\Repository\ScambiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


use Symfony\Bundle\SecurityBundle\Security;

#[ORM\Entity(repositoryClass: ScambiRepository::class)]
class Scambi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  //  #[ORM\Column(type: Types::ARRAY)]
  //  private array $status = [];

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'ScambiUser')]
    private Collection $userTarget;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(length: 255)]
//    private ?string $statusString = null;
    private string $statusString = '';

    #[ORM\Column]
    private int $fromUser = 1;



    public function __construct()
    {

      //  private Security $security,
        $this->userTarget = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();

        $string = '';

    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection<int, User>
     */
    public function getUserTarget(): Collection
    {
        return $this->userTarget;
    }

    public function addUserTarget(User $userTarget): static
    {
        if (!$this->userTarget->contains($userTarget)) {
            $this->userTarget->add($userTarget);
        }

        return $this;
    }

    public function removeUserTarget(User $userTarget): static
    {
        $this->userTarget->removeElement($userTarget);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatusString(): ?string
    {
        return $this->statusString;
    }

    public function setStatusString(string $statusString): static
    {
        $this->statusString = $statusString;

        return $this;
    }



    public function getFromUser(): ?int
    {
        return $this->fromUser;
    }

    public function setFromUser(int $fromUser): static
    {
        $this->fromUser = $fromUser;

        return $this;
    }




}
