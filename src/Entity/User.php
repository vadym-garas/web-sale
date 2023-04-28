<?php

namespace App\Entity;

use App\Entity\State;
use App\Entity\Traits\StateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity()]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    const ROLE_BUYER = 'ROLE_BUYER';
    const ROLE_VENDOR = 'ROLE_VENDOR';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    use StateTrait;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $user_roles = [];

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private string $password;

    #[ORM\Column(length: 45)]
    private string $phone;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Phone::class, fetch: 'LAZY')]
    private Collection $phones;


    /**
     * @param string $email
     * @param string $password
     * @param int $state
     */
    public function __construct(string $email = '', string $password = '', int $state = State::STATE_DISABLE)
    {
        $this->email = $email;
        $this->changePassword($password);
        $this->state = $state;
        // $this->phones = new ArrayCollection();
    }

    /**
     * @param array $userData
     * @return static
     */
    public static function createFromArray(array $userData): static
    {
        if (!isset($userData['email']) || !isset($userData['password'])) {
            throw new InvalidArgumentException();
        }
        return new static($userData['email'], $userData['password']);
    }

    /**
     * @param string $stringData
     * @return static
     */
    public static function createFromString(string $stringData): static
    {
        $userData = explode('|', $stringData);
        return new static($userData[0], $userData[1]);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->changeEmail($email);
    }

    /**
     * @param mixed $email)
     */
    public function changeEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return void
     */
    public function changePassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param mixed $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->changePassword($password);
    }


    /**
     * @return Collection
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    /**
     * @param Phone $phone
     */
    public function setPhones(Phone $phone): void
    {
        $this->phones->add($phone);
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->user_roles;
        $roles[] = static::ROLE_BUYER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->user_roles = $roles;
        return $this;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function addRole(string $role): static
    {
        $this->user_roles[] = $role;
        return $this;
    }

    public function eraseCredentials()
    {
        return;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones->add($phone);
            $phone->setUser($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phones->removeElement($phone)) {
            // set the owning side to null (unless already changed)
            if ($phone->getUser() === $this) {
                $phone->setUser(null);
            }
        }

        return $this;
    }
}