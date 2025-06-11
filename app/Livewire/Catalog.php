<?php

namespace App\Livewire;

use App\CartService;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Catalog extends Component
{
    use WithPagination;

    #[Url]
    public string $sort = 'title:asc';
    #[Url]
    public string $search = '';

    public function render()
    {
        $products = Product::query()
            ->when(!empty($this->search), function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('code', 'like', "%{$this->search}%");
            })
            ->orderBy(...explode(':', $this->sort))
            ->paginate();

        return view('livewire.catalog', [
            'products' => $products,
            'cartService' => app(CartService::class),
        ]);
    }

    public function toggleCartItem(string $productSlug): void
    {
        $cartService = app(CartService::class);

        if ($cartService->hasProduct($productSlug)) {
            $cartService->removeProduct($productSlug);
        } else {
            $cartService->addProduct($productSlug);
        }
    }
}
