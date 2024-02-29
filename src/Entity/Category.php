<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\{ApiResource, Get, GetCollection, Post, Put, Delete};
use App\Dto\CategoryDto;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource()]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public string $name;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: true)]
    private DateTime|null $createdAt = null;

     
    #[Groups("read")]
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
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

     /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function removeProduct(Product $product): self
    {
        
        $this->products->removeElement($product);

        if ($this->products->removeElement($product)) {
            if ($product->getCategory() === $this){
                $product->setCategory(null);
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
