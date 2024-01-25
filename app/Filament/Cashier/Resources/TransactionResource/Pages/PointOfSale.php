<?php

namespace App\Filament\Cashier\Resources\TransactionResource\Pages;

use App\Models\Product;
use App\Models\Transaction;
use Filament\Support\RawJs;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Cashier\Resources\TransactionResource;

class PointOfSale extends Page
{
    protected static string $resource = TransactionResource::class;
    protected static ?string $title = 'New Transaction';
    public $scan_barcode;
    public $products;
    public $scanned_products = [];
    public $quantity = 1;
    public $total_quantity = 0;
    public $grand_total = 0;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('payTransaction')
            ->label('Payment')
            ->form([
                TextInput::make('payment_amount')
                    ->label('Amount')
                    ->numeric()
                    ->autofocus()
                    ->prefix('₱')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->rules('numeric|min:'.$this->grand_total)
                    ->required(),
            ])->disabled(fn () => $this->scanned_products === [])
            ->requiresConfirmation()
            ->action(function (array $data): void {
                DB::beginTransaction();
                $transaction = Transaction::create([
                    'transaction_number' => 'TRN-'.date('YmdHis'),
                    'quantity' => $this->total_quantity,
                    'sub_total' => $this->grand_total,
                    'tax' => 0,
                    'discount' => 0,
                    'grand_total' => $this->grand_total,
                    'amount_paid' => $data['payment_amount'],
                    'change' => $data['payment_amount'] - $this->grand_total,
                ]);

                foreach ($this->scanned_products as $key => $product) {
                    $transaction->transaction_items()->create([
                        'product_id' => $product['id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                        'sub_total' => $product['subtotal'],
                    ]);
                }

                //deduct to stock
                foreach ($this->scanned_products as $key => $product) {
                    $product = Product::find($product['id']);
                    $updated_quantity = $product->inventories()->where('quantity', '>', 0)->first()->quantity - $this->total_quantity;
                    $product->inventories()->where('quantity', '>', 0)->first()->update(['quantity' => $updated_quantity]);
                }
                DB::commit();
                Notification::make()
                ->title('Transaction successful')
                ->body('The transaction has been successfully processed.')
                ->success()
                ->send();
                $this->scanned_products = [];
                $this->total_quantity = 0;
                $this->grand_total = 0;
                $this->scan_barcode = null;

            }),
            Action::make('cancel')
            ->label('Cancel')
            ->color('danger')
            ->action(fn () => $this->reset()),


        ];
    }

    public function updatedScanBarcode()
    {
        $product = Product::where('barcode', $this->scan_barcode)->first();

        if ($product) {
            // Check if the product is already in the list
            $existingProduct = $this->findProductInList($product->id);

            if ($existingProduct) {
                // If the product is already in the list, increment the quantity
                $existingProduct['quantity'] += 1;
                $existingProduct['subtotal'] += $product->price;
                $this->total_quantity += 1;
                // $this->quantity = $existingProduct['quantity'];

                $this->scanned_products = array_map(function ($product) use ($existingProduct) {
                    if ($product['id'] === $existingProduct['id']) {
                        return $existingProduct;
                    }

                    return $product;
                }, $this->scanned_products);
                $this->grand_total += $product->price;
            } else {
                // If the product is not in the list, add it with quantity 1
                $newProduct = [
                    'id' => $product->id,
                    'barcode' => $product->barcode,
                    'name' => $product->name,
                    'quantity' => 1,
                    'price' => $product->price,
                    'subtotal' => $product->price,
                ];
                $this->scanned_products[] = $newProduct;
                $this->grand_total += $product->price;
                $this->total_quantity += 1;
            }

            // Clear the scanned barcode field
            $this->scan_barcode = null;

        } else {

                Notification::make()
                ->title('Product not found')
                ->body('The scanned barcode does not match any product in the database.')
                ->danger()
                ->send();

                $this->scan_barcode = null;
        }
    }

    // Helper function to find a product in the list by its ID
    private function findProductInList($productId)
    {
        foreach ($this->scanned_products as $key => $product) {
            if ($product['id'] === $productId) {
                return $this->scanned_products[$key];
            }
        }

        return null;
    }

    protected static string $view = 'filament.cashier.resources.transaction-resource.pages.point-of-sale';

}
