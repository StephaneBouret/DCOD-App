<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 * @Vich\Uploadable
 */
class Images
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
    private $illustration;

    /**
     * @Vich\UploadableField(mapping="imagiers_images", fileNameProperty="illustration")
     * @var File
     */
    private $pictureFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploadedAt;

    /**
     * @ORM\ManyToOne(targetEntity=PictureBook::class, inversedBy="picture")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pictureBook;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    public function setPictureFile(File $illustration = null)
    {
        $this->pictureFile = $illustration;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($illustration) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->uploadedAt = new \DateTime('now');
        }
    }

    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeInterface $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    public function getPictureBook(): ?PictureBook
    {
        return $this->pictureBook;
    }

    public function setPictureBook(?PictureBook $pictureBook): self
    {
        $this->pictureBook = $pictureBook;

        return $this;
    }
}
