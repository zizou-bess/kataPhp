<?php

declare(strict_types=1);

namespace App;

use DateTimeImmutable;

final class Cart
{
    private array $lines = [];
    private DiscountService $discounts;
    private float $cachedTotal = 0.0;

    public function __construct(?DiscountService $discounts = null)
    {
        $this->discounts = $discounts ?? new DiscountService();
    }

    public function add(Product $p, int $qty): void
    {
        if ($qty == 0) {
            return;
        }
        if (!isset($this->lines[$p->getId()])) {
            $this->lines[$p->getId()] = ['product' => $p, 'qty' => 0];
        }
        $this->lines[$p->getId()]['qty'] += $qty;
        $this->cachedTotal += ($p->getPriceCents() * $qty);
    }

    public function totalCents(DateTimeImmutable $now): int
    {
        $subtotal = 0;
        foreach ($this->lines as $line) {
            $subtotal += $line['product']->getPriceCents() * $line['qty'];
        }

        $discountPercent = $this->discounts->getDiscountPercent($now);
        $discount = (int) round($subtotal * ($discountPercent / 100));
        
        $subtotal -= $discount ;
        $vat = (int) round($subtotal * 0.20);

        return (int) round($subtotal + $vat);
    }

    public function rawLines(): array
    {
        return $this->lines;
    }
}
