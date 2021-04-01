<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Formation
 *
 * @ORM\Table(name="formation")
 * @ORM\Entity(repositoryClass="App\Repository\FormationRepository")
 * @Vich\Uploadable
 */
class Formation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     *  @Assert\NotBlank
     * @Assert\NotNull
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     *  @Assert\NotBlank
     * @Assert\NotNull
     */
    private $description;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="formation", fileNameProperty="imageName")
     *
     * @var File|null
     */
    public $imageFile;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    public $imageName;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var integer
     *
     * @ORM\Column(name="place", type="integer", nullable=false)
     */
    private $place;

    /**
     * @var \Discount
     *
     * @ORM\ManyToOne(targetEntity="Discount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iddiscount", referencedColumnName="id")
     * })
     */
    private $iddiscount;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=false)
     *  @Assert\NotBlank
     * @Assert\NotNull
     */
    private $location;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", precision=10, scale=0, nullable=false)
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", precision=10, scale=0, nullable=false)
     */
    private $lng;

    /**
     * @var float
     *
     * @ORM\Column(name="La_g", type="float", precision=10, scale=0, nullable=false)
     */
    private $La_g;

    /**
     * @var float
     *
     * @ORM\Column(name="La_i", type="float", precision=10, scale=0, nullable=false)
     */
    private $La_i;

    /**
     * @var float
     *
     * @ORM\Column(name="Ra_g", type="float", precision=10, scale=0, nullable=true)
     */
    private $Ra_g;

    /**
     * @var float
     *
     * @ORM\Column(name="Ra_i", type="float", precision=10, scale=0, nullable=true)
     */
    private $Ra_i;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            //  $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getIddiscount(): ?Discount
    {
        return $this->iddiscount;
    }

    public function setIddiscount(?Discount $iddiscount): self
    {
        $this->iddiscount = $iddiscount;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getLaG(): ?float
    {
        return $this->La_g;
    }

    public function setLaG(float $La_g): self
    {
        $this->La_g = $La_g;

        return $this;
    }

    public function getLaI(): ?float
    {
        return $this->La_i;
    }

    public function setLaI(float $La_i): self
    {
        $this->La_i = $La_i;

        return $this;
    }

    public function getRaG(): ?float
    {
        return $this->Ra_g;
    }

    public function setRaG(float $Ra_g): self
    {
        $this->Ra_g = $Ra_g;

        return $this;
    }

    public function getRaI(): ?float
    {
        return $this->Ra_i;
    }

    public function setRaI(float $Ra_i): self
    {
        $this->Ra_i = $Ra_i;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
}
