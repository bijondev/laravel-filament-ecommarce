<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderResource::getEloquentQuery()
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label("Order Id")->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label("Customer")->searchable(),
                Tables\Columns\TextColumn::make('grand_total')->money("BDT"),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state): mixed => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'deliverd' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->icon(fn($state): mixed => match ($state) {
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'deliverd' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('payment_status')->sortable()->searchable()->badge(),
                // Tables\Columns\TextColumn::make('shipping_amo÷unt')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('shipping_method')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->sortable()->label("Order date")->dateTime()
            ])
            ->actions([
                Action::make('View Order')
                    ->url(fn(Order $record) => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('info')->icon('heroicon-o-eye')
            ])
        ;
    }
}