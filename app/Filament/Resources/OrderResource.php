<?php

namespace App\Filament\Resources;

use App\Enums\OrderDeliveryTypeEnum;
use App\Enums\OrderPaymentTypeEnum;
use App\Enums\OrderStatusEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Pages\CreateOrder;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $slug = 'orders';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationBadgeTooltip = 'Нових замовлень';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('status')
                    ->options(OrderStatusEnum::class)
                    ->required()
                    ->hiddenOn('create'),
                TextInput::make('customer_name')
                    ->required(),
                TextInput::make('customer_email')
                    ->email()
                    ->required(),
                TextInput::make('customer_phone')
                    ->required(),
                Select::make('delivery_type')
                    ->options(OrderDeliveryTypeEnum::class)
                    ->required(),
                Select::make('payment_type')
                    ->options(OrderPaymentTypeEnum::class)
                    ->required(),
                TextInput::make('total_amount')
                    ->required()
                    ->integer()
                    ->readOnly()
                    ->prefix('₴'),
                Repeater::make('products')
                    ->relationship('orderProducts')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'title')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($state) {
                                    $product = Product::find($state);
                                    $set('price', $product->price);
                                    self::updateTotalAmount($get, $set);
                                }
                            }),
                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotalAmount($get, $set);
                            }),
                        TextInput::make('price')
                            ->numeric()
                            ->readOnly()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotalAmount($get, $set);
                            }),
                    ])
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotalAmount($get, $set);
                    })
                    ->deleteAction(
                        fn ($action) => $action->after(function (Get $get, Set $set) {
                            self::updateTotalAmount($get, $set);
                        })
                    ),
            ]);
    }

    private static function updateTotalAmount(Get $get, Set $set): void
    {
        $products = $get('products');

        if (!is_array($products)) {
            $set('total_amount', 0);
            return;
        }

        $total = collect($products)->reduce(function ($carry, $product) {
            return $carry + ((int) $product['quantity'] * (int) $product['price']);
        }, 0);

        $set('total_amount', $total);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SelectColumn::make('status')
                    ->options(OrderStatusEnum::class)
                    ->selectablePlaceholder(false),
                TextColumn::make('customer_name'),
                TextColumn::make('customer_email'),
                TextColumn::make('customer_phone'),
                TextColumn::make('delivery_type'),
                TextColumn::make('payment_type'),
                TextColumn::make('total_amount'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(OrderStatusEnum::class),
                SelectFilter::make('delivery_type')
                    ->options(OrderDeliveryTypeEnum::class),
                SelectFilter::make('payment_type')
                    ->options(OrderPaymentTypeEnum::class),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['customer_name', 'customer_email', 'customer_phone'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', OrderStatusEnum::NEW)->count();
    }
}
