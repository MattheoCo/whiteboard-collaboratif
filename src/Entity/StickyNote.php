<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\StickyNoteRepository::class)]
#[ORM\Table(name: 'sticky_notes')]
class StickyNote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $text = null;

    #[ORM\Column(type: 'integer')]
    private int $x = 0;

    #[ORM\Column(type: 'integer')]
    private int $y = 0;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(type: 'boolean')]
    private bool $done = false;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $timestamp;

    public function getId(): ?int { return $this->id; }
    public function getText(): ?string { return $this->text; }
    public function setText(?string $t): self { $this->text = $t; return $this; }
    public function getX(): int { return $this->x; }
    public function setX(int $x): self { $this->x = $x; return $this; }
    public function getY(): int { return $this->y; }
    public function setY(int $y): self { $this->y = $y; return $this; }
    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $c): self { $this->color = $c; return $this; }
    public function isDone(): bool { return $this->done; }
    public function setDone(bool $d): self { $this->done = $d; return $this; }
    public function getTimestamp(): \DateTimeInterface { return $this->timestamp; }
    public function setTimestamp(\DateTimeInterface $t): self { $this->timestamp = $t; return $this; }
}
