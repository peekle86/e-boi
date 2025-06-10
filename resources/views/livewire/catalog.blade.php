<div>
    @if($search)
        <p class="h3">Результати пошуку за запитом: {{ $search }}</p>
    @endif
    <div class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <label for="inputPassword6" class="col-form-label">Сортувати по: </label>
        </div>
        <div class="col-auto">
            <select class="form-select" aria-label="Default select example" wire:model.live="sort">
                <option selected value="title:asc">назві (за зростанням)</option>
                <option selected value="title:desc">назві (за спаданням)</option>
                <option selected value="price:asc">ціні (за зростанням)</option>
                <option selected value="price:desc">ціні (за спаданням)</option>
                <option selected value="stock_quantity:asc">наявності (за зростанням)</option>
                <option selected value="stock_quantity:desc">наявності (за спаданням)</option>
            </select>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-3">
        @foreach($products as $product)
            <div class="col">
                <div class="card shadow-sm">
                    <img src="{{ $product->getFirstMediaUrl() }}" alt="{{ $product->title }}" onclick="window.location.href='{{ route('products.show', $product) }}'" style="height: 256px;object-fit: cover;cursor: pointer;">
                    <div class="card-body">
                        <p class="h3 card-text"><a href="{{ route('products.show', $product) }}">{{ $product->title }}</a></p>
                        <smal>{{ $product->stock_quantity }} в наявності</smal>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-body-secondary">{{ Number::currency($product->price) }}</small>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $products->links() }}
</div>
