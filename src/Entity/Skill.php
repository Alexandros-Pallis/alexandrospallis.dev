<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ORM\Table(name: 'skills')]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: SkillCategory::class, inversedBy: 'skills')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?SkillCategory $category = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $proficiency;

    #[ORM\Column(type: Types::INTEGER)]
    private int $displayOrder = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?SkillCategory
    {
        return $this->category;
    }

    public function setCategory(?SkillCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getProficiency(): int
    {
        return $this->proficiency;
    }

    public function setProficiency(int $proficiency): static
    {
        $this->proficiency = $proficiency;

        return $this;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): static
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }
}
