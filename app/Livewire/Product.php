<?php

namespace App\Livewire;

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
        ]);
    }
}
