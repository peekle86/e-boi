<?php

namespace App\Livewire;

use App\CartService;
use App\Enums\OrderDeliveryTypeEnum;
use App\Enums\OrderPaymentTypeEnum;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Session;
use Livewire\Component;

class Cart extends Component
{
    #[Session]
    public ?string $customer_name = '';
    #[Session]
    public ?string $customer_email = '';
    #[Session]
    public ?string $customer_phone = '';
    #[Session]
    public int $delivery_type = OrderDeliveryTypeEnum::PICKUP->value;
    #[Session]
    public int $payment_type = OrderPaymentTypeEnum::ON_RECEIPT->value;

    public function render()
    {
        return view('livewire.cart', [
            'cartService' => app(CartService::class),
            'deliveryTypeEnums' => OrderDeliveryTypeEnum::cases(),
            'paymentTypeEnums' => OrderPaymentTypeEnum::cases(),
        ]);
    }

    public function checkout()
    {
        $cartService = app(CartService::class);

        $orderData = $this->validate([
            'customer_name' => ['required', 'string', 'min:3', 'max:255'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['required', 'string', 'min:3', 'max:255'],
            'delivery_type' => ['required', Rule::enum(OrderDeliveryTypeEnum::class)],
            'payment_type' => ['required', Rule::enum(OrderPaymentTypeEnum::class)],
        ]);

        $orderData['total_amount'] = $cartService->getTotalAmount();

        try {
            DB::transaction(function () use ($orderData, $cartService) {
                $order = Order::create($orderData);

                foreach ($cartService->getAllProducts() as $product) {
                    $cartItem = $cartService->getCartItem($product->slug);
                    $order->products()->attach($product, ['quantity' => $cartItem['quantity'], 'price' => $product->price]);;
                }
            });

            $cartService->clear();

            $this->redirect(route('cart.success'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function removeProduct(string $productSlug): void
    {
        $cartService = app(CartService::class);

        $cartService->removeProduct($productSlug);
    }

    public function minusQuantity(string $slug)
    {
        $cartService = app(CartService::class);

        $cartService->minusQuantity($slug);
    }

    public function plusQuantity(string $slug)
    {
        $cartService = app(CartService::class);

        $cartService->plusQuantity($slug);
    }
}
