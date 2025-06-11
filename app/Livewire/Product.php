<?php

namespace App\Livewire;

use App\CartService;
use Livewire\Attributes\Locked;
use Livewire\Component;
use App\Models\Product as ProductModel;

class Product extends Component
{
    #[Locked]
    public ProductModel $product;

    public function mount(ProductModel $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.product', [
            'images' => $this->product->getMedia(),
            'cartService' => app(CartService::class),
        ]);
    }

    public function toggleCartItem(): void
    {
        $cartService = app(CartService::class);

        if ($cartService->hasProduct($this->product->slug)) {
            $cartService->removeProduct($this->product->slug);
        } else {
            $cartService->addProduct($this->product->slug);
        }
    }
}
