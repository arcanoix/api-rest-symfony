<?php
// api/src/Entity/Product.php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource]
class Product // The class name will be used to name exposed resources
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column] 
    #[Assert\NotBlank]
    public string $name = '';

    #[ORM\Column(type: 'text')]
    public string $description = '';

    #[ORM\Column(name: "created_at", type: "datetime", nullable: true)]
    public DateTime|null $createdAt = null;

    #[ORM\Column]
    #[Assert\Range(minMessage: 'The price must be superior to 0.', min: 0)]
    public float $price = -1.0;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    public ?Category $category = null;


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

    public function getCategory()
    {
                return $this->category;
    }

    public function setCategory(Category $category)
    {
                $category->addProduct($this);
                return $this->category = $category;
    }

    public function __construct()
    {
                $this->createdAt = new DateTime();
    }
    
}