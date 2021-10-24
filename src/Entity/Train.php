<?php

namespace App\Entity;

use App\Repository\TrainRepository;
use App\Util\SlugUtil;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrainRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Train
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Locomotive::class, inversedBy="train", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $locomotive;

    /**
     * @ORM\OneToMany(targetEntity=Car::class, mappedBy="train")
     */
    private $cars;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="trains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $station;

    /**
     * @ORM\OneToMany(targetEntity=TrainLog::class, mappedBy="Train", cascade={"persist", "remove"})
     */
    private $logs;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocomotive(): ?Locomotive
    {
        return $this->locomotive;
    }

    public function setLocomotive(Locomotive $locomotive): self
    {
        $this->locomotive = $locomotive;

        return $this;
    }

    /**
     * @return Collection|Car[]
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
            $car->setTrain($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getTrain() === $this) {
                $car->setTrain(null);
            }
        }

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
        $this->slug = SlugUtil::generateSlug([$this->getLocomotive()->getTypeAndNumber(), $this->getStation()->getName()]);

        return $this;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): self
    {
        $this->station = $station;

        return $this;
    }

    /**
     * @return Collection|TrainLog[]
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(TrainLog $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setTrain($this);
        }

        return $this;
    }

    public function removeLog(TrainLog $log): self
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getTrain() === $this) {
                $log->setTrain(null);
            }
        }

        return $this;
    }
}
