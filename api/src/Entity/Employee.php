<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Enum\Position;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Rollerworks\Component\PasswordStrength\Validator\Constraints as RollerworksPassword;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[UniqueEntity('login')]
#[ApiResource(
    collectionOperations: ['post'],
    itemOperations: ['get'],
    denormalizationContext: ['groups' => ['post']],
    normalizationContext: ['groups' => ['get']],
)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\Length(min: 5)]
    #[Groups(['get', 'post'])]
    private string $login;

    #[ORM\Column(type: 'string', length: 255)]
    #[RollerworksPassword\PasswordStrength(minStrength: 4, minLength: 8, message: 'Password should contain both lowercase and uppercase alpha characters, a digit and a special character ')]
    #[Groups(['post'])]
    private string $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Choice(callback: 'getAllPositions', message: 'This should be developer, hr, manager or tester')]
    #[Groups(['get', 'post'])]
    private string $position;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Regex(pattern: '/\+48\d{9}/', message: 'This value should be in format +48XXXXXXXXX')]
    #[Groups(['get', 'post'])]
    private string $phoneNumber;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $externalId;

    /**
     * @return Position[]
     */
    public static function getAllPositions(): array
    {
        return array_map(fn($a) => $a->value, Position::cases());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(?int $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }
}
