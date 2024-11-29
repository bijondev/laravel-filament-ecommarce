<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('id')
                //     ->required()
                //     ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label("Order Id")->searchable(),
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
                Tables\Columns\TextColumn::make('created_at')->sortable()->label("Order date")->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('View Order')
                    ->url(fn(Order $record) => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('info')->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}