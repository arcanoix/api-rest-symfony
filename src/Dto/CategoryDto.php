<?php

namespace App\Dto;

class CategoryDto
{
    private string $name;

    static function of(string $name): CategoryDto
    {
        $dto = new CategoryDto();
        $dto->setName($name);
        return $dto;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}