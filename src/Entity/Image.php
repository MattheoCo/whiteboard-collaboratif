<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\ImageRepository::class)]
#[ORM\Table(name: 'images')]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 1024)]
    private string $url;

    #[ORM\Column(type: 'integer')]
    private int $x = 0;

    #[ORM\Column(type: 'integer')]
    private int $y = 0;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $addedBy = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $timestamp;

    public function getId(): ?int { return $this->id; }
    public function getUrl(): ?string { return $this->url; }
    public function setUrl(string $url): self { $this->url = $url; return $this; }
    public function getX(): int { return $this->x; }
    public function setX(int $x): self { $this->x = $x; return $this; }
    public function getY(): int { return $this->y; }
    public function setY(int $y): self { $this->y = $y; return $this; }
    public function getAddedBy(): ?string { return $this->addedBy; }
    public function setAddedBy(?string $u): self { $this->addedBy = $u; return $this; }
    public function getTimestamp(): \DateTimeInterface { return $this->timestamp; }
    public function setTimestamp(\DateTimeInterface $t): self { $this->timestamp = $t; return $this; }
}
