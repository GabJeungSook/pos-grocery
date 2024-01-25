<?php

namespace App\Filament\Cashier\Resources\TransactionResource\Pages;

use App\Filament\Cashier\Resources\TransactionResource;
use App\Models\Product;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;

class PointOfSale extends Page
{
    protected static string $resource = TransactionResource::class;
    protected static ?string $title = 'New Transaction';
    public $scan_barcode;
    public $products;
    public $scanned_products = [];
    public $quantity = 1;



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
                // $this->quantity = $existingProduct['quantity'];

                $this->scanned_products = array_map(function ($product) use ($existingProduct) {
                    if ($product['id'] === $existingProduct['id']) {
                        return $existingProduct;
                    }

                    return $product;
                }, $this->scanned_products);
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

    public function mount()
    {
        $this->products = null;
    }
}
