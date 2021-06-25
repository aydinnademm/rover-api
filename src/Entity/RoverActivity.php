<?php

namespace App\Entity;

use App\Repository\RoverActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoverActivityRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class RoverActivity implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Rover::class, inversedBy="activities")
     */
    private $rover;

    /**
     * @ORM\ManyToOne(targetEntity=Plateau::class, inversedBy="activities")
     */
    private $plateau;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $command;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $compassDirection;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRover(): ?Rover
    {
        return $this->rover;
    }

    public function setRover(?Rover $rover): self
    {
        $this->rover = $rover;

        return $this;
    }

    public function getPlateau(): ?Plateau
    {
        return $this->plateau;
    }

    public function setPlateau(?Plateau $plateau): self
    {
        $this->plateau = $plateau;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCommand(): ?string
    {
        return $this->command;
    }

    public function setCommand(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCompassDirection(): ?string
    {
        return $this->compassDirection;
    }

    public function setCompassDirection(string $compassDirection): self
    {
        $this->compassDirection = $compassDirection;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    public function jsonSerialize(): array
    {
        return [
            'plateau' => [
                'id'   => $this->getPlateau()->getId(),
                'name' => $this->getPlateau()->getName()
            ],
            'latitude'           => $this->getLatitude(),
            'longitude'          => $this->getLongitude(),
            'last_activity_time' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'compass_direction'  => $this->getCompassDirection()
        ];
    }
}
