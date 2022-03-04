<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category {

    #[Groups(["category:r", "product:r"])]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', unique: true)]
    private int $id;

    #[Groups(["category:r", "category:w", "product:r", "product:w"])]
    #[Assert\NotBlank]
    #[ORM\Column(nullable: false)]
    private ?string $name;


    #[ORM\OneToMany(mappedBy: "category", targetEntity: Product::class, orphanRemoval: true)]
    private Collection $products;

    #[Pure] public function __construct() {
        $this->products = new ArrayCollection();
    }

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

    /**
     * @return Collection
     */
    public function getProducts(): Collection {
        return $this->products;
    }

    public function addProduct(Product $product): self {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->name;
    }
}
