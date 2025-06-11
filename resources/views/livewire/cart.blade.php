<div>
    <div class="row">
        <div class="col-md-6 col-12">
            <form wire:submit="checkout">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="mb-3">
                    <label for="checkoutName" class="form-label">Ім'я</label>
                    <input type="text" class="form-control" id="checkoutName" placeholder="Іван Горивода" wire:model.live="customer_name" required>
                </div>
                <div class="mb-3">
                    <label for="checkoutPhone" class="form-label">Телефон</label>
                    <input type="text" class="form-control" id="checkoutPhone" placeholder="name@example.com" wire:model.live="customer_phone" required>
                </div>
                <div class="mb-3">
                    <label for="checkoutEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="checkoutEmail" wire:model.live="customer_email" required>
                </div>
                </div>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="mb-3">
                    <label for="checkoutName" class="form-label">Спосіб доставки</label>
                    <select class="form-select" wire:model.live="delivery_type" required>
                        @foreach($deliveryTypeEnums as $deliveryTypeEnum)
                            <option value="{{ $deliveryTypeEnum->value }}">{{ $deliveryTypeEnum->getLabel() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="checkoutName" class="form-label">Спосіб оплати</label>
                    <select class="form-select" wire:model.live="payment_type" required>
                        @foreach($paymentTypeEnums as $paymentTypeEnum)
                            <option value="{{ $paymentTypeEnum->value }}">{{ $paymentTypeEnum->getLabel() }}</option>
                        @endforeach
                    </select>
                </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-lg btn-success" @disabled($cartService->isEmpty())>Підтвердити замовлення</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-12">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Товар</th>
                    <th scope="col">К-сть</th>
                    <th scope="col">Ціна</th>
                    <th scope="col">Сума</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($cartService->getAllProducts() as $product)
                    <tr>
                        <th scope="row">{{ $product->title }}</th>
                        <td>{{ $product->quantity }}
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-secondary" wire:click="minusQuantity('{{ $product->slug }}')">-</button>
                                <button type="button" class="btn btn-sm btn-secondary" wire:click="plusQuantity('{{ $product->slug }}')">+</button>
                            </div>
                        </td>
                        <td>{{ Number::currency($product->price) }}</td>
                        <td>{{ Number::currency($product->sum) }}</td>
                        <td><button type="button" class="btn btn-sm btn-danger" wire:click="removeProduct('{{ $product->slug }}')"><i class="bi bi-trash"></i></button></td>
                    </tr>
                @endforeach
                <tr><td colspan="6" class="text-end">Сума: {{ Number::currency($cartService->getTotalAmount()) }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
