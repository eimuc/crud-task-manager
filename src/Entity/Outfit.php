<?php

namespace App\Entity;

use App\Repository\OutfitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OutfitRepository::class)
 */
class Outfit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Empty type field")
     */
    private $type;


    /**
    * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Empty type field"))
     */
    private $size;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Empty about field")
     */
    private $about;

      /**
     * @ORM\Column(type="integer")
     */
    private $master_id;

    /**
     * @ORM\ManyToOne(targetEntity=Master::class, inversedBy="outfits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $master;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function getMasterId(): ?int
    {
        return $this->master_id;
    }

    public function setMasterId(int $master_id): self
    {
        $this->master_id = $master_id;

        return $this;
    }

    public function getMaster(): ?Master
    {
        return $this->master;
    }

    public function setMaster(?Master $master): self
    {
        $this->master = $master;

        return $this;
    }
}
