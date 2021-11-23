<?php

namespace App\Entity;

use App\Repository\LevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LevelRepository::class)
 */
class Level
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=PictureBook::class, mappedBy="level")
     */
    private $pictureBooks;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="level")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=GameFiles::class, mappedBy="level")
     */
    private $gameFiles;

    public function __construct()
    {
        $this->pictureBooks = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->gameFiles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection|PictureBook[]
     */
    public function getPictureBooks(): Collection
    {
        return $this->pictureBooks;
    }

    public function addPictureBook(PictureBook $pictureBook): self
    {
        if (!$this->pictureBooks->contains($pictureBook)) {
            $this->pictureBooks[] = $pictureBook;
            $pictureBook->setLevel($this);
        }

        return $this;
    }

    public function removePictureBook(PictureBook $pictureBook): self
    {
        if ($this->pictureBooks->removeElement($pictureBook)) {
            // set the owning side to null (unless already changed)
            if ($pictureBook->getLevel() === $this) {
                $pictureBook->setLevel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setLevel($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getLevel() === $this) {
                $product->setLevel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameFiles[]
     */
    public function getGameFiles(): Collection
    {
        return $this->gameFiles;
    }

    public function addGameFile(GameFiles $gameFile): self
    {
        if (!$this->gameFiles->contains($gameFile)) {
            $this->gameFiles[] = $gameFile;
            $gameFile->setLevel($this);
        }

        return $this;
    }

    public function removeGameFile(GameFiles $gameFile): self
    {
        if ($this->gameFiles->removeElement($gameFile)) {
            // set the owning side to null (unless already changed)
            if ($gameFile->getLevel() === $this) {
                $gameFile->setLevel(null);
            }
        }

        return $this;
    }
}
