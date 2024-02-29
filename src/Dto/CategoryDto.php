<?php

namespace App\Dto;

class CategoryDto
{
    private string $name;
    private string $createdAt;

    static function of(string $name): CategoryDto
    {
        $dto = new CategoryDto();
        $dto->setName($name);
        return $dto;
    }

    /**
     * @param string $title
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $content
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

}