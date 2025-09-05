<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\MapMarkerRepository::class)]
#[ORM\Table(name: 'map_markers')]
class MapMarker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'float')]
    private float $lat;

    #[ORM\Column(type: 'float')]
    private float $lng;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $timestamp;

    #[ORM\Column(type: 'string', length: 7, nullable: true)]
    private ?string $color = '#FF0000';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $n): self { $this->name = $n; return $this; }
    public function getLat(): float { return $this->lat; }
    public function setLat(float $v): self { $this->lat = $v; return $this; }
    public function getLng(): float { return $this->lng; }
    public function setLng(float $v): self { $this->lng = $v; return $this; }
    public function getTimestamp(): \DateTimeInterface { return $this->timestamp; }
    public function setTimestamp(\DateTimeInterface $t): self { $this->timestamp = $t; return $this; }
    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $c): self { $this->color = $c; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): self { $this->description = $d; return $this; }
}
