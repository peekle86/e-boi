<?php

namespace App\Models;

use App\Enums\OrderDeliveryTypeEnum;
use App\Enums\OrderPaymentTypeEnum;
use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_type',
        'payment_type',
        'total_amount',
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class,
        'delivery_type' => OrderDeliveryTypeEnum::class,
        'payment_type' => OrderPaymentTypeEnum::class,
    ];

    protected $attributes = [
        'status' => OrderStatusEnum::NEW,
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'order_products',
            'order_id',
            'product_id'
        )
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }
}
