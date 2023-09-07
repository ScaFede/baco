<?php

namespace App\Entity;

use App\Repository\ScambiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

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

//    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'scambiUserSender')]
    #[ORM\ManyToOne(inversedBy: 'scambiUserSender')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userSender = null;

    #[ORM\Column(nullable: true)]
    private ?bool $scambioConfermato = null;

    #[ORM\Column(nullable: true)]
    private ?bool $confermaSender = null;

    #[ORM\Column(nullable: true)]
    private ?bool $confermaTarget = null;

    #[ORM\ManyToOne(inversedBy: 'ScambiUserTargetRel')]
    private ?CompetenzeBis $userTargetCompetenzaRel = null;

    #[ORM\ManyToOne(inversedBy: 'ScambiUserSenderRel')]
    private ?CompetenzeBis $userSenderCompetenzaRel = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Donazione = null;



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

    public function getUserSender(): ?User
    {
        return $this->userSender;
    }

    public function setUserSender(?User $userSender): static
    {
        $this->userSender = $userSender;

        return $this;
    }

    public function isScambioConfermato(): ?bool
    {
        return $this->scambioConfermato;
    }

    public function setScambioConfermato(?bool $scambioConfermato): static
    {
        $this->scambioConfermato = $scambioConfermato;

        return $this;
    }




    public function isConfirmedByBothUsers(): bool
    {
        return $this->isScambioConfermato() && $this->getUserSender()->hasConfirmed() && $this->allUserTargetsConfirmed();
    }

    private function allUserTargetsConfirmed(): bool
    {
        foreach ($this->getUserTarget() as $userTarget) {
            if (!$userTarget->hasConfirmed()) {
                return false;
            }
        }
        return true;
    }

    public function isConfermaSender(): ?bool
    {
        return $this->confermaSender;
    }

    public function setConfermaSender(?bool $confermaSender): static
    {
        $this->confermaSender = $confermaSender;

        return $this;
    }

    public function isConfermaTarget(): ?bool
    {
        return $this->confermaTarget;
    }

    public function setConfermaTarget(?bool $confermaTarget): static
    {
        $this->confermaTarget = $confermaTarget;

        return $this;
    }

    public function getUserTargetCompetenzaRel(): ?CompetenzeBis
    {
        return $this->userTargetCompetenzaRel;
    }

    public function setUserTargetCompetenzaRel(?CompetenzeBis $userTargetCompetenzaRel): static
    {
        $this->userTargetCompetenzaRel = $userTargetCompetenzaRel;

        return $this;
    }

    public function getUserSenderCompetenzaRel(): ?CompetenzeBis
    {
        return $this->userSenderCompetenzaRel;
    }

    public function setUserSenderCompetenzaRel(?CompetenzeBis $userSenderCompetenzaRel): static
    {
        $this->userSenderCompetenzaRel = $userSenderCompetenzaRel;

        return $this;
    }

    public function isDonazione(): ?bool
    {
        return $this->Donazione;
    }

    public function setDonazione(?bool $Donazione): static
    {
        $this->Donazione = $Donazione;

        return $this;
    }



}
