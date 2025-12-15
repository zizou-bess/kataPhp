<?php

declare(strict_types=1);

namespace App;

final class Product
{
    private string $id;
    private ?string $name = null;
    private int $priceCents = 0;

    public function __construct(string $id, string $name, int $priceCents)
    {
        if (trim($id) === '') {
               throw new \InvalidArgumentException('Product ID cannot be empty');
        }

        if ($priceCents < 0) {
            throw new \InvalidArgumentException('Product price cannot be negative');
        }

        $this->id = $id;
        $this->name = $name;
        $this->priceCents = (int)$priceCents;
    }

    public function getPriceCents(): int
    {
        return $this->priceCents;
    }

    public function setName(string $name): string
    {
        $this->name = trim($name);
        return $this->name;
    }

    public function equals(Product $other): bool
    {
        return $this->id === $other->id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
