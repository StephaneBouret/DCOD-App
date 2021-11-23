<?php

namespace App\Entity;

use App\Repository\IndexWordsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndexWordsRepository::class)
 */
class IndexWords
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $page;

    /**
     * @ORM\Column(type="integer")
     */
    private $tome;

    /**
     * @ORM\ManyToOne(targetEntity=Alphabet::class, inversedBy="words")
     * @ORM\JoinColumn(nullable=false)
     */
    private $alphabet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $level;

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

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getTome(): ?int
    {
        return $this->tome;
    }

    public function setTome(int $tome): self
    {
        $this->tome = $tome;

        return $this;
    }

    public function getAlphabet(): ?Alphabet
    {
        return $this->alphabet;
    }

    public function setAlphabet(?Alphabet $alphabet): self
    {
        $this->alphabet = $alphabet;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }
}
