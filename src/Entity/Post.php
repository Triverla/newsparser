<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    /**
     * @ORM\Column(type="string")
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $created;

    /**
     * @ORM\Column(type="string")
     */
    private string $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $dateTime): void
    {
        $this->created = $dateTime;
    }

    public function getUpdated(): \DateTime
    {
        return $this->created;
    }

    public function setUpdated(\DateTime $dateTime): void
    {
        $this->created = $dateTime;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->getTitle(),
            'desc' => $this->getDescription(),
            'date' => $this->getDate(),
            'image' => $this->getImage(),
        ];
    }
}
