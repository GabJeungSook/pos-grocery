<?php

namespace App\Filament\Cashier\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Cashier\Resources\ViewTransactionResource\Pages;
use App\Filament\Cashier\Resources\ViewTransactionResource\RelationManagers;
use Filament\Resources\Components\Tab;

class ViewTransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_number')
                ->label('Transaction Number')
                ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                ->label('Total Quantity')
                ->numeric()
                ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                ->label('Total Payable')
                ->badge()
                ->color('success')
                ->formatStateUsing(fn ($state) => '₱ '.number_format($state, 2))
                ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Date of Transaction')
                ->dateTime('F d, Y h:i A')
                ->sortable(),
            ])
            ->filters([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListViewTransactions::route('/'),
        ];
    }
}
