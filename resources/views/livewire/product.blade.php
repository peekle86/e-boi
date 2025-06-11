<div>
    <div class="row">
        <div class="col-md-5 col-12">
    <div id="carouselExampleIndicators" class="carousel slide">
        @if ($images->count() > 1)
        <div class="carousel-indicators">
            @foreach ($images as $image)
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}" @if($loop->first) class="active" aria-current="true" @endif></button>
            @endforeach
        </div>
        @endif
        <div class="carousel-inner">
            @foreach ($images as $image)
            <div @class(['carousel-item', 'active' => $loop->first])>
                <img src="{{ $image->getUrl() }}" class="d-block w-100" alt="{{ $product->title }}">
            </div>
            @endforeach
        </div>
        @if ($images->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
            @elseif($images->count() < 1)
                <div class="carousel-inner">
                    <div @class(['carousel-item', 'active'])>
                        <img src="{{ $product->getFirstMediaUrl() }}" class="d-block w-100" alt="{{ $product->title }}">
                    </div>
                </div>
        @endif
    </div>
        </div>
        <div class="col-md-7 col-12">
            <h1>{{ $product->title }}</h1>
            <p>Опис: {{ $product->description }}</p>
            <p>В наявності: {{ $product->stock_quantity }}шт.</p>
            <p>Ціна: {{ Number::currency($product->price) }}</p>
            <button type="button" class="btn @if($cartService->hasProduct($product->slug)) btn-primary @else btn-outline-primary @endif" wire:click="toggleCartItem()">
                @if($cartService->hasProduct($product->slug))
                    В корзині
                @else
                    Додати в корзину
                @endif
            </button>
        </div>
    </div>
</div>
