<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, minMessage: "Too short")]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private $description;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\Column(type: 'datetime')]
    private $creationDate;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $responsibleWorker;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $priority;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\Range(
        min: 'now Europe/Warsaw'
    )]
    private $deadline;

    private $authorName;

    private $responsibleWorkerName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author->getUsername();
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    #[ORM\PreFlush]
    public function setCreationDate(): self
    {
        if (isset($this->creationDate)){
            return $this;
        }
        $this->creationDate = new \DateTime();
        return $this;
    }

    public function getResponsibleWorker(): ?User
    {
        return $this->responsibleWorker;
    }

    public function setResponsibleWorker(?User $responsibleWorker): self
    {
        $this->responsibleWorker = $responsibleWorker;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getAuthorName()
    {
        return $this->authorName = $this->author->getUsername();
    }

    public function getResponsibleWorkerName()
    {
        if (isset($this->responsibleWorker)){
            return $this->responsibleWorkerName = $this->responsibleWorker->getUsername();
        }
            return null;
    }
}
