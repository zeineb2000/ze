<?php

namespace App\Entity;

use App\Repository\DiscountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountRepository::class)
 */
class Discount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="percentege", type="float", precision=10, scale=0, nullable=false)
     */
    private $percentege;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPercentege(): ?float
    {
        return $this->percentege;
    }

    public function setPercentege(float $percentege): self
    {
        $this->percentege = $percentege;

        return $this;
    }
}
