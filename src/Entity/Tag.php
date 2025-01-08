<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    use TimestampableEntity;

    #[Groups(['select_box'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['select_box'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
