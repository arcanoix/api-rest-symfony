<?php

namespace App\Entity;

use ApiPlatform\Metadata\{ApiResource};
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
    )]
class Product 
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column] 
    #[Assert\NotBlank]
    #[Groups(['read', 'write'])]
    public string $name = '';

    #[ORM\Column(type: 'text')]
    #[Groups(['read', 'write'])]
    public string $description = '';

    #[ORM\Column(name: "created_at", type: "datetime", nullable: true)]
    #[Groups(['read'])]
    public DateTime|null $createdAt = null;

    #[ORM\Column]
    #[Assert\Range(minMessage: 'The price must be superior to 0.', min: 0)]
    #[Assert\NotBlank]
    #[Groups(['read', 'write'])]
    public float $price = -1.0;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private $category;

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }


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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }


    public function __construct()
    {
                $this->createdAt = new DateTime();
    }

    
    public static function create(string $name, string $description, float $price, Category $category): Product
    {
        
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setCategory($category);
        
        return $product;
    }
}