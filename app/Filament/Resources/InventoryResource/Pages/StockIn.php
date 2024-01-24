<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use Filament\Forms\Form;
use App\Models\Inventory;
use App\Models\Product;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\InventoryResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;

class StockIn extends Page
{
    protected static string $resource = InventoryResource::class;

    protected static ?string $model = Inventory::class;

    protected static string $view = 'filament.resources.inventory-resource.pages.stock-in';

    public $date;
    public $time;
    public ?array $data = [];


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                ->schema([
                    Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                    TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required(),
                    DatePicker::make('date')
                    ->label('Date')->readonly()
                    ->closeOnDateSelection()
                    ->required(),
                    TimePicker::make('time')
                    ->label('Time')
                    ->readOnly()
                    ->withoutSeconds()
                    ->timezone('Asia/Manila')
                    ->required(),
                ])
                ->columns(2)

            ])
            ->statePath('data');
    }

    public function mount()
    {
        $this->date = now()->format('F d, Y');
        $this->time = now()->format('h:i A');
        $this->form->fill([
            'date' => $this->date,
            'time' => $this->time,
        ]);
    }

    public function save()
    {
        $this->validate([
            'data.product_id' => 'required',
            'data.quantity' => 'required|numeric',
            'data.date' => 'required',
            'data.time' => 'required',
        ]);

        $inventory = Inventory::create([
            'product_id' => $this->data['product_id'],
            'quantity' => $this->data['quantity'],
        ]);

        Notification::make()
        ->title('Success')
        ->body('Stock successfully added!')
        ->success()
        ->send();

        $this->form->fill([
            'date' => $this->date,
            'time' => $this->time,
        ]);

    }
}
