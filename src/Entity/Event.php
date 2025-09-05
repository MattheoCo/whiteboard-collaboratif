<?php
namespace App\Entity;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'events')]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $start;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(type: 'string', length: 7, nullable: true)]
    private ?string $color = '#3498db';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isAllDay = false;

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $t): self { $this->title = $t; return $this; }
    public function getStart(): \DateTimeInterface { return $this->start; }
    public function setStart(\DateTimeInterface $s): self { $this->start = $s; return $this; }
    public function getEnd(): ?\DateTimeInterface { return $this->end; }
    public function setEnd(?\DateTimeInterface $e): self { $this->end = $e; return $this; }
    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $c): self { $this->color = $c; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): self { $this->description = $d; return $this; }
    public function isAllDay(): bool { return $this->isAllDay ?? false; }
    public function getIsAllDay(): bool { return $this->isAllDay ?? false; }
    public function setIsAllDay(?bool $a): self { $this->isAllDay = $a; return $this; }
}
