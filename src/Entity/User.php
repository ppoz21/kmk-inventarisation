<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Util\SlugUtil;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $forgetPasswordHash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilePhoto;

    /**
     * @ORM\ManyToMany(targetEntity=Station::class, mappedBy="users")
     */
    private $stations;

    /**
     * @ORM\OneToOne(targetEntity=APIKey::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $apiKey;

    /**
     * @ORM\ManyToMany(targetEntity=ToDoList::class, mappedBy="user")
     */
    private $toDoLists;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $slug;

    #[Pure]
    public function __construct()
    {
        $this->stations = new ArrayCollection();
        $this->toDoLists = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name.' '.$this->surname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getForgetPasswordHash(): ?string
    {
        return $this->forgetPasswordHash;
    }

    public function setForgetPasswordHash(): self
    {
        $forgetPasswordHash = bin2hex(random_bytes(20));

        $this->forgetPasswordHash = $forgetPasswordHash;

        return $this;
    }

    public function removeForgetPasswordHash(): self
    {
        $this->forgetPasswordHash = null;

        return $this;
    }

    public function getProfilePhoto(): ?string
    {
        return $this->profilePhoto;
    }

    public function setProfilePhoto(?string $profilePhoto): self
    {
        $this->profilePhoto = $profilePhoto;

        return $this;
    }

    /**
     * @return Collection|Station[]
     */
    public function getStations(): Collection
    {
        return $this->stations;
    }

    public function addStation(Station $station): self
    {
        if (!$this->stations->contains($station)) {
            $this->stations[] = $station;
            $station->addUser($this);
        }

        return $this;
    }

    public function removeStation(Station $station): self
    {
        if ($this->stations->contains($station)) {
            $this->stations->removeElement($station);
            $station->removeUser($this);
        }

        return $this;
    }

    public function getApiKey(): ?APIKey
    {
        return $this->apiKey;
    }

    public function setApiKey(APIKey $apiKey): self
    {
        $this->apiKey = $apiKey;

        // set the owning side of the relation if necessary
        if ($apiKey->getUser() !== $this) {
            $apiKey->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|ToDoList[]
     */
    public function getToDoLists(): Collection
    {
        return $this->toDoLists;
    }

    public function addToDoList(ToDoList $toDoList): self
    {
        if (!$this->toDoLists->contains($toDoList)) {
            $this->toDoLists[] = $toDoList;
            $toDoList->addUser($this);
        }

        return $this;
    }

    public function removeToDoList(ToDoList $toDoList): self
    {
        if ($this->toDoLists->removeElement($toDoList)) {
            $toDoList->removeUser($this);
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
        $this->slug = SlugUtil::generateSlug([$this->getName(), $this->getSurname()]);

        return $this;
    }
}
