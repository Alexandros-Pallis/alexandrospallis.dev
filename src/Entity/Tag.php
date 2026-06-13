<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $slug;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'tags')]
    private Collection $projects;

    /**
     * @var Collection<int, TimelineEntry>
     */
    #[ORM\ManyToMany(targetEntity: TimelineEntry::class, mappedBy: 'tags')]
    private Collection $timelineEntries;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->timelineEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addTag($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TimelineEntry>
     */
    public function getTimelineEntries(): Collection
    {
        return $this->timelineEntries;
    }

    public function addTimelineEntry(TimelineEntry $timelineEntry): static
    {
        if (!$this->timelineEntries->contains($timelineEntry)) {
            $this->timelineEntries->add($timelineEntry);
            $timelineEntry->addTag($this);
        }

        return $this;
    }

    public function removeTimelineEntry(TimelineEntry $timelineEntry): static
    {
        if ($this->timelineEntries->removeElement($timelineEntry)) {
            $timelineEntry->removeTag($this);
        }

        return $this;
    }
}
