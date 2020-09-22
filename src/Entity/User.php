<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
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
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $loginName;

    /**
     * @var string
     * @ORM\Column (type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     * @ORM\Column (type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column (type="string", length=255)
     */
    private $middlename;

    /**
     * @ORM\Column (type="date", nullable=true)
     */
    private $birthday;

//    region Добавить сущность стран и связать таблицы

//    /**
//     * @ORM\Column (type="string", length=255)
//     */
//    private $country;

//    endregion


//     region Добавить функционал соли пароля

//    /**
//     * @ORM\Column (type="string", length=255)
//     */
//    private $salt;

//     endregion

    /**
     * @var integer
     * @ORM\Column (type="integer")
     */
    private $phone;

    /**
     * @var boolean
     * @ORM\Column (type="boolean", options={"default":true})
     */
    private $activate = true;

    /**
     * @var boolean
     * @ORM\Column (type="boolean", options={"default":false})
     */
    private $hidden = false;

    /**
     * @var DateTime
     * @ORM\Column (type="datetime", nullable=true)
     */
    private $creationTime;

    /**
     * @var DateTime
     * @ORM\Column (name="updatetime", type="datetime", nullable=true)
     */
    private $updateTime;

    /**
     * @var DateTime
     * @ORM\Column (name="visit_time", type="datetime", nullable=true)
     */
    private $visitTime;

    public function __construct()
    {
        $this->creationTime = new DateTime();
        $this->updateTime = new DateTime();
        $this->visitTime = new DateTime();
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
//        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getLoginName(): string
    {
        return $this->loginName;
    }

    /**
     * @param string $loginName
     * @return User
     */
    public function setLoginName(string $loginName): User
    {
        $this->loginName = $loginName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return User
     */
    public function setSurname(string $surname): User
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getMiddlename(): string
    {
        return $this->middlename;
    }

    /**
     * @param string $middlename
     * @return User
     */
    public function setMiddlename(string $middlename): User
    {
        $this->middlename = $middlename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhone(): ?int
    {
        return $this->phone;
    }

    /**
     * @param int $phone
     * @return User
     */
    public function setPhone(int $phone): User
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActivate(): bool
    {
        return $this->activate;
    }

    /**
     * @param bool $activate
     * @return User
     */
    public function setActivate(bool $activate): User
    {
        $this->activate = $activate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden(): ?bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     * @return User
     */
    public function setHidden(bool $hidden): User
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * @param mixed $creationTime
     * @return User
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdateTime(): DateTime
    {
        return $this->updateTime;
    }

    /**
     * @param DateTime $updateTime
     * @return User
     */
    public function setUpdateTime(DateTime $updateTime): User
    {
        $this->updateTime = $updateTime;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getVisitTime(): DateTime
    {
        return $this->visitTime;
    }

    /**
     * @param DateTime $visitTime
     * @return User
     */
    public function setVisitTime(DateTime $visitTime): User
    {
        $this->visitTime = $visitTime;
        return $this;
    }


}
