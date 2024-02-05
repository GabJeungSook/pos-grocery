<x-filament-panels::page>
    <div class="max-w-full">
            <input type="text" id="scan" class="w-full rounded-md shadow-md rounded-sm px-2 py-1 border border-gray-300 focus:outline-none focus:ring-none" placeholder="Scan Barcode..." style="outline: none; border-color: gray;" autocomplete="off" autofocus wire:model.live="scan_barcode">
            <style>
                #search:focus {
                    border-color: gray;
                }
            </style>

<table class="w-full border border-gray-300 shadow-lg overflow-hidden" style="margin-top: 2rem; background-color: #f3f6f4;  border-style: solid; border-color: gray; border-width: 2px;">
    <thead class="text-black" style="border-style: solid; border-color: black; border-width: 2px;">
        <tr>
            <th class="py-2 px-4 border-b border-black font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">Barcode</th>
            <th class="py-2 px-4 border-b border-black font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">Product</th>
            <th class="py-2 px-4 border-b border-black font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">Quantity</th>
            <th class="py-2 px-4 border-b border-black font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">Price</th>
            <th class="py-2 px-4 border-b border-black font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">Sub Total</th>
        </tr>
    </thead>
    <tbody>
        @if($scanned_products)
            @forelse ($scanned_products as $product)
                <tr class="">
                    <td class="py-2 px-4 border-r border-gray-400 text-center font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">{{$product['barcode']}}</td>
                    <td class="py-2 px-4 border-r border-gray-400 text-center font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">{{$product['name']}}</td>
                    <td class="py-2 px-4 border-r border-gray-400 text-center font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">
                        {{-- <input type="number" class="text-center rounded-lg"  id="{{$product["id"]}}" wire:model.live="quantity" value="{{$product['quantity']}}"> --}}
                        {{$product['quantity']}}
                    </td>
                    <td class="py-2 px-4 border-r border-gray-400 text-center font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">₱ {{number_format($product['price'], 2)}}</td>
                    <td class="py-2 px-4 border-r border-gray-400 text-center font-mono" style="border-right-style: solid; border-color: gray; border-width: 2px;">₱ {{number_format($product['price'] * $product['quantity'], 2)}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">No Transaction</td>
                </tr>
            @endforelse
        @endif
    </tbody>
</table>
    <div>
        <div class="text-right space-y-4" style=" margin-top: 3rem; padding-right: 4px;">
            <div>
                <strong>Sub Total: ₱ {{ number_format(array_sum(array_column($scanned_products, 'subtotal')), 2) }}</strong>
            </div>
            <div>
                <strong>Tax ({{$tax->name}} % {{$tax->percentage}}): ₱ {{number_format(array_sum(array_column($scanned_products, 'tax')), 2)}} </strong>
            </div>
            <div>
                <strong>Discount ({{$discount_name}} % {{number_format($discount_percentage, 2)}}): ₱ {{number_format($total_discount, 2)}}</strong>
            </div>
            <div>
                <strong class="text-3xl">Grand Total: ₱ {{ number_format($grand_total, 2) }}</strong>
            </div>
        </div>
    </div>
    {{-- <div style="display: flex;  justify-content: end; margin-top: 5rem">
        <div style="margin-right: 0.5rem; align-items: center; ">
            <x-filament::button type="submit" style=" padding-left: 1rem; padding-right: 1rem;" size="md" wire:click="saveTransaction">Pay</x-filament::button>
        </div>
        <div style="display: flex; align-items: center;">
            <button type="button" class="self-end rounded-md bg-white mt-10 px-2.5 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-600 hover:bg-gray-50">Cancel</button>
        </div>
    </div> --}}
    </div>
</x-filament-panels::page>
