<?php

namespace App;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    const SESSION_KEY = 'cart';

    public function getAllItems(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function getAllProductSlugs(): array
    {
        return array_keys(self::getAllItems());
    }

    public function getAllProducts(): Collection
    {
        return Product::whereIn('slug', self::getAllProductSlugs())
            ->get()
            ->map(function (Product $product) {
                $cartItem = $this->getCartItem($product->slug);

                $product->setAttribute('quantity', $cartItem['quantity']);
                $product->setAttribute('sum', $cartItem['quantity'] * $product->price);

                return $product;
            });
    }

    public function getCartItem(string $slug): array
    {
        return $this->getAllItems()[$slug];
    }

    public function getProduct(string $slug): Product
    {
        return Product::where('slug', $slug)->firstOrFail();
    }

    public function getTotalAmount(): int
    {
        return $this->getAllProducts()->sum('price');
    }

    public function getTotalCount(): int
    {
        return count(self::getAllItems());
    }

    public function isEmpty(): bool
    {
        return ! $this->isNotEmpty();
    }

    public function isNotEmpty(): bool
    {
        return self::getTotalCount() > 0;
    }

    public function addProduct(string $slug, int $quantity = 1): void
    {
        if ($this->hasProduct($slug)) {
            $this->plusQuantity($slug);
        } else {
            $cart = $this->getAllItems();
            $cart[$slug] = [
                'quantity' => $quantity,
            ];
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    public function removeProduct(string $slug): void
    {
        if ($this->hasProduct($slug)) {
            $cart = $this->getAllItems();
            unset($cart[$slug]);
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    public function hasProduct(string $slug): bool
    {
        return isset(self::getAllItems()[$slug]);
    }

    public function plusQuantity(string $slug): void
    {
        if ($this->hasProduct($slug)) {
            $cart = $this->getAllItems();
            $cart[$slug]['quantity']++;
            Session::put(self::SESSION_KEY, $cart);
        } else {
            $this->addProduct($slug);
        }
    }

    public function minusQuantity(string $slug): void
    {
        if ($this->hasProduct($slug)) {
            $cart = $this->getAllItems();
            $cart[$slug]['quantity']--;

            if ($cart[$slug]['quantity'] < 1) {
                $this->removeProduct($slug);
            } else {
                Session::put(self::SESSION_KEY, $cart);
            }
        }
    }

    public function setProperty(string $name, mixed $value): void
    {
        Session::put($name, $value);
    }

    public function getProperty(string $name): mixed
    {
        return Session::get($name);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}
