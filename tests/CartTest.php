<?php

declare(strict_types=1);

namespace App\Tests;

use App\Cart;
use App\Product;
use App\DiscountService;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CartTest extends TestCase
{
    private function blackFridayOf(int $year): DateTimeImmutable
    {
        $d = new DateTimeImmutable("$year-11-01 Europe/Paris");
        $lastDay = $d->modify('last day of this month');
        while ($lastDay->format('N') !== '5') {
            $lastDay = $lastDay->modify('-1 day');
        }
        return $lastDay->setTime(12, 0);
    }

    public function test_add_and_total_without_discount(): void
    {
        $cart = new Cart(new DiscountService());
        $p1 = new Product('A-1', 'Adapter USB-C', 1999);
        $p2 = new Product('B-2', 'Câble HDMI', 1299);
        $cart->add($p1, 2);
        $cart->add($p2, 1);
        $now = new DateTimeImmutable('2025-03-10 12:00:00 Europe/Paris');
        $this->assertSame(6356, $cart->totalCents($now));
    }

    public function test_black_friday_discount_then_vat(): void
    {
        $cart = new Cart(new DiscountService());
        $p = new Product('C-3', 'Clavier', 5000);
        $cart->add($p, 1);
        $bf = $this->blackFridayOf(2025);
        $this->assertSame(4800, $cart->totalCents($bf));
    }

    public function test_invalid_inputs_are_rejected(): void
    {
        $cart = new Cart(new DiscountService());
        $this->expectException(\InvalidArgumentException::class);
        new Product('', 'Sans ID', 100);
        $this->expectException(\InvalidArgumentException::class);
        new Product('X-1', 'Prix négatif', -50);
        $p = new Product('P-1', 'Test', 100);
        $this->expectException(\InvalidArgumentException::class);
        $cart->add($p, 0);
        $this->expectException(\InvalidArgumentException::class);
        $cart->add($p, -2);
    }

    public function test_totals_are_int_and_never_negative(): void
    {
        $cart = new Cart(new DiscountService());
        $p = new Product('D-4', 'Sticker', 1);
        $cart->add($p, 1);
        $now = new DateTimeImmutable('2025-01-01 10:00:00 Europe/Paris');
        $total = $cart->totalCents($now);
        $this->assertIsInt($total);
        $this->assertGreaterThanOrEqual(0, $total);
    }
}
