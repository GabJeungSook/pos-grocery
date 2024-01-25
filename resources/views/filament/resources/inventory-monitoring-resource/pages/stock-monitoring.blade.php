<x-filament-panels::page>
    <div class="max-w-full">
        <p>
            <span class="text-md font-normal">Rows that are <span class="font-semibold underline" style="color:#d10303;">RED</span> need to be restocked.</span>
        </p>
        <table class="w-full border border-gray-300 shadow-lg overflow-hidden mt-2" style="background-color: #f3f6f4;  border-style: solid; border-color: gray; border-width: 2px;">
            <thead class="text-black" style="border-style: solid; border-color: black; border-width: 2px;">
                <tr>
                    <th class="py-2 px-4 border-b border-black" style="border-right-style: solid; border-color: gray; border-width: 2px;">Product</th>
                    <th class="py-2 px-4 border-b border-black" style="border-right-style: solid; border-color: gray; border-width: 2px;">Reorder Point</th>
                    <th class="py-2 px-4 border-b border-black" style="border-right-style: solid; border-color: gray; border-width: 2px;">Stock Quantity</th>
                    <!-- Add more headers if needed -->
                </tr>
            </thead>
            <tbody>
                    @forelse ($products as $product)
                    @if ($product->inventories->sum('quantity') <= $product->reorder_point)
                    <tr class="" style="background-color: #d10303; color:white;">
                    @else
                    <tr class="" style="background-color: #93c47d; color:white;">
                    @endif
                    <td class="py-2 px-4 border-r border-gray-400 text-center" style="border-right-style: solid; border-color: gray; border-width: 2px;">{{$product->name}}</td>
                    <td class="py-2 px-4 border-r border-gray-400 text-center" style="border-right-style: solid; border-color: gray; border-width: 2px;">{{$product->reorder_point}}</td>
                    <td class="py-2 px-4 border-r border-gray-400 text-center" style="border-right-style: solid; border-color: gray; border-width: 2px;">{{$product->inventories->sum('quantity')}}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-2 px-4 border-r border-gray-400 text-center" style="border-right-style: solid; border-color: gray; border-width: 2px;">No Record Yet</td>
                    </tr>
                    @endforelse
            </tbody>
        </table>
    </div>


</x-filament-panels::page>
