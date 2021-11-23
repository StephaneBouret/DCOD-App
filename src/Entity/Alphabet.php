<?php

namespace App\Entity;

use App\Repository\AlphabetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlphabetRepository::class)
 */
class Alphabet
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
    private $letter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=IndexWords::class, mappedBy="alphabet")
     */
    private $words;

    public function __construct()
    {
        $this->words = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLetter();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLetter(): ?string
    {
        return $this->letter;
    }

    public function setLetter(string $letter): self
    {
        $this->letter = $letter;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|IndexWords[]
     */
    public function getWords(): Collection
    {
        return $this->words;
    }

    public function addWord(IndexWords $word): self
    {
        if (!$this->words->contains($word)) {
            $this->words[] = $word;
            $word->setAlphabet($this);
        }

        return $this;
    }

    public function removeWord(IndexWords $word): self
    {
        if ($this->words->removeElement($word)) {
            // set the owning side to null (unless already changed)
            if ($word->getAlphabet() === $this) {
                $word->setAlphabet(null);
            }
        }

        return $this;
    }
}
