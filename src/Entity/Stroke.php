<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Stroke
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $data = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $vector = null;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $createdAt;

    public function __construct(?string $data = null, ?array $vector = null)
    {
        $this->data = $data;
        $this->vector = $vector;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getData(): ?string { return $this->data; }
    public function setData(?string $d): self { $this->data = $d; return $this; }
    public function getVector(): ?array { return $this->vector; }
    public function setVector(?array $v): self { $this->vector = $v; return $this; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
}
