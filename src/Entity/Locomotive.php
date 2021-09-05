<?php

namespace App\Entity;

use App\Repository\LocomotiveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocomotiveRepository::class)
 */
class Locomotive
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
    private $typeAndNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $painting;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToOne(targetEntity=Train::class, mappedBy="locomotive", cascade={"persist", "remove"})
     */
    private $train;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAndNumber(): ?string
    {
        return $this->typeAndNumber;
    }

    public function setTypeAndNumber(string $typeAndNumber): self
    {
        $this->typeAndNumber = $typeAndNumber;

        return $this;
    }

    public function getPainting(): ?string
    {
        return $this->painting;
    }

    public function setPainting(string $painting): self
    {
        $this->painting = $painting;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getTrain(): ?Train
    {
        return $this->train;
    }

    public function setTrain(Train $train): self
    {
        $this->train = $train;

        // set the owning side of the relation if necessary
        if ($train->getLocomotive() !== $this) {
            $train->setLocomotive($this);
        }

        return $this;
    }
}
