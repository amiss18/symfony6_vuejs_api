<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product {


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[Assert\NotBlank]
    #[ORM\Column()]
    private ?string $name;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?float $price;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    #[ORM\Column(type: "text", length: 255)]
    private ?string $description;

    #[Assert\Valid]
    #[ORM\ManyToOne(targetEntity: Category::class, cascade: ["persist"], inversedBy: "products")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string {
        return $this->price;
    }

    public function setPrice(string $price): self {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category {
        return $this->category;
    }

    public function setCategory(?Category $category): self {
        $this->category = $category;

        return $this;
    }
}
