<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: true)]
    public DateTime|null $createdAt = null;

    
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    private $products;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        //$category->product = null;
        $this->products->removeElement($product);

        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->category === $this){
                $product->category = null;
            }
        }

        return $this;
    }

    public static function create(string $name): Category
    {
        $category = new Category();
        $category->setName($name);
        
        return $category;
    }
}
