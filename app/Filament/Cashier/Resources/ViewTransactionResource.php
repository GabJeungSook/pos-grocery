<?php

namespace App\Filament\Cashier\Resources;

use App\Filament\Cashier\Resources\ViewTransactionResource\Pages;
use App\Filament\Cashier\Resources\ViewTransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;

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
                //
            ])
            ->actions([
                Action::make('view_receipt')
                ->label('View Receipt')
                ->url(fn (Transaction $record): string => route('filament.cashier.resources.transactions.receipt', $record))
                ->button()
                ->icon('heroicon-o-eye')
                ->openUrlInNewTab()

                // Tables\Actions\EditAction::make(),
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
