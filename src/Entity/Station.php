<?php

namespace App\Entity;

use App\Repository\StationRepository;
use App\Util\SlugUtil;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StationRepository::class)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Station
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="stations")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $scheme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=StationLog::class, mappedBy="station")
     */
    private $logs;

    /**
     * @ORM\OneToMany(targetEntity=Train::class, mappedBy="station")
     */
    private $trains;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->logs = new ArrayCollection();
        $this->trains = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function setUsers(?Collection $users)
    {
        $this->users = $users;
    }

    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    public function setScheme(?string $scheme): self
    {
        $this->scheme = $scheme;

        return $this;
    }

    public function getSlug(): ?string
    {
        if ($this->slug)
        {
            return $this->slug;
        }
        $this->setSlug();
        return $this->getSlug();
    }

    /**
     * @ORM\PrePersist()
     */
    public function setSlug(): self
    {
        $this->slug = SlugUtil::generateSlug($this->getName());

        return $this;
    }

    /**
     * @return Collection|StationLog[]
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(StationLog $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setStation($this);
        }

        return $this;
    }

    public function removeLog(StationLog $log): self
    {
        if ($this->logs->contains($log)) {
            $this->logs->removeElement($log);
            // set the owning side to null (unless already changed)
            if ($log->getStation() === $this) {
                $log->setStation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Train[]
     */
    public function getTrains(): Collection
    {
        return $this->trains;
    }

    public function addTrain(Train $train): self
    {
        if (!$this->trains->contains($train)) {
            $this->trains[] = $train;
            $train->setStation($this);
        }

        return $this;
    }

    public function removeTrain(Train $train): self
    {
        if ($this->trains->removeElement($train)) {
            // set the owning side to null (unless already changed)
            if ($train->getStation() === $this) {
                $train->setStation(null);
            }
        }

        return $this;
    }
}
