<?php

namespace App\Filament\Cashier\Resources\TransactionResource\Pages;

use App\Models\Tax;
use App\Models\Product;
use Filament\Forms\Get;
use App\Models\Discount;
use App\Models\Transaction;
use Filament\Support\RawJs;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
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
    public $tax;
    public $total_tax;
    public $total_discount;
    public $selected_discount;
    public $discount_name;
    public $discount_percentage;

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
                    'tax' => $this->total_tax,
                    'discount' => $this->total_discount,
                    'discount_type' => $this->discount_name,
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
            Action::make('addDiscount')
            ->label('Add Discount')
            ->form([
                Select::make('discount')
                ->options(Discount::all()->pluck('name', 'id'))
                ->required()
                ->live()
                ->afterStateUpdated(function ($get, $set) {
                    $discount = Discount::find($get('discount'));
                    $set('percentage', $discount->percentage);
                })
                ->visible(fn ($get) => $get('is_custom') === false),
                Toggle::make('is_custom')
                ->label('Custom Discount')
                ->onIcon('heroicon-o-check')
                ->offIcon('heroicon-o-x-mark')
                ->onColor('success')
                ->offColor('danger')
                ->live()
                ->afterStateUpdated(function ($get, $set) {
                    if ($get('is_custom') === true) {
                        $set('discount', null);
                        $set('percentage', 0);
                    }
                }),
                TextInput::make('percentage')
                ->label('Percentage')
                ->numeric()
                ->prefix('%')
                ->required()
                ->default(0)
                ->readOnly(fn ($get) => $get('is_custom') === false),
            ])
            ->disabled(fn () => $this->scanned_products === [])
            ->requiresConfirmation()
            ->action(function (array $data) {
               if($data['is_custom'] != true)
               {
                $this->selected_discount = Discount::find($data['discount']);
                $this->discount_name = $this->selected_discount->name;
                $this->discount_percentage = $this->selected_discount->percentage;
               }else{
                $this->discount_name = 'Custom';
                $this->discount_percentage = $data['percentage'];
               }
               //sum of scanned_products subtotal

               $subtotal = array_sum(array_column($this->scanned_products, 'subtotal'));
               $tax = array_sum(array_column($this->scanned_products, 'tax'));
               $this->total_tax = $tax;
               $total = $subtotal + $tax;
               $this->total_discount = $total * ($this->discount_percentage / 100);
               $this->grand_total = $total - $this->total_discount;

                Notification::make()
                ->title('Discount added')
                ->body('The discount has been successfully added to the transaction.')
                ->success()
                ->send();
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
                $existingProduct['tax'] += ($product->price * ($this->tax->percentage / 100));
                // $this->quantity = $existingProduct['quantity'];

                $this->scanned_products = array_map(function ($product) use ($existingProduct) {
                    if ($product['id'] === $existingProduct['id']) {
                        return $existingProduct;
                    }

                    return $product;
                }, $this->scanned_products);
                 $subtotal = array_sum(array_column($this->scanned_products, 'subtotal'));
                 $tax = array_sum(array_column($this->scanned_products, 'tax'));
                 $this->total_tax = $tax;
                 $total = $subtotal + $tax;
                 $this->total_discount = $total * ($this->discount_percentage / 100);
                 $this->grand_total = $total - $this->total_discount;
                //  $this->grand_total += $product->price + ($product->price * ($this->tax->percentage / 100)) - $this->total_discount;

                // $this->grand_total += $product->price;
            } else {
                // If the product is not in the list, add it with quantity 1
                $newProduct = [
                    'id' => $product->id,
                    'barcode' => $product->barcode,
                    'name' => $product->name,
                    'quantity' => 1,
                    'price' => $product->price,
                    'subtotal' => $product->price,
                    'tax' => ($product->price * ($this->tax->percentage / 100))
                ];
                $this->scanned_products[] = $newProduct;
                $subtotal = array_sum(array_column($this->scanned_products, 'subtotal'));
                $tax = array_sum(array_column($this->scanned_products, 'tax'));
                $this->total_tax = $tax;
                $total = $subtotal + $tax;
                $this->total_discount = $total * ($this->discount_percentage / 100);
                $this->grand_total = $total - $this->total_discount;
                // $this->total_discount = $this->grand_total * ($this->discount_percentage / 100);
                // $this->grand_total += $product->price + ($product->price * ($this->tax->percentage / 100)) - $this->total_discount;
                // $this->grand_total += $product->price;
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

    public function mount()
    {
        $this->tax = Tax::first();
    }

    protected static string $view = 'filament.cashier.resources.transaction-resource.pages.point-of-sale';

}
