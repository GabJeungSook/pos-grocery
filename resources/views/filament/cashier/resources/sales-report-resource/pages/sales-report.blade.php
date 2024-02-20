<x-filament-panels::page>
    <div>
    <div class="flex justify-between">
        <div  class="flex">
            <div class="px-1">
                <x-filament::button  type="button" icon="heroicon-o-printer" class="btn btn-primary w-32" onclick="printDiv('printarea')">Print</x-filament::button>
            </div>
        </div>
        <div class="mt-20">
            {{-- {{$this->form}} --}}
        </div>
    </div>

    @if($record->count() > 0)
            <!-- Transcation Table -->
            <div id="printarea">
                <table class="w-full" style="margin-top: 30px;">
                    <thead>
                        <tr>
                            <th class="border border-gray-500 px-2 py-2">Date</th>
                            <th class="border border-gray-500 px-2 py-2">Transaction No.</th>
                            <th class="border border-gray-500 px-2 py-2">Total Quantity</th>
                            <th class="border border-gray-500 px-2 py-2">Sub Total</th>
                            <th class="border border-gray-500 px-2 py-2">Grand Total</th>
                            <th class="border border-gray-500 px-2 py-2">Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Data (Replace this with your actual data) -->

                        @foreach ($record as $item)
                        <tr>
                            <td class="border border-gray-500 px-2 py-2">{{Carbon\Carbon::parse($item->created_at)->format('M d, Y')}}</td>
                            <td class="border border-gray-500 px-2 py-2">{{strtoupper($item->transaction_number)}}</td>
                            <td class="border border-gray-500 px-2 py-2 text-center">{{$item->quantity}}</td>
                            <td class="border border-gray-500 px-2 py-2 text-center">₱ {{number_format($item->sub_total, 2)}}</td>
                            <td class="border border-gray-500 px-2 py-2 text-center">₱ {{number_format($item->grand_total, 2)}}</td>
                            <td class="border border-gray-500 px-2 py-2 text-center">₱ {{number_format($item->amount_paid, 2)}}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

                <div class="flex justify-end font-semibold text-lg" style="margin-right: 3.5rem; margin-top: 2rem;">
                    <p>Total Sales Today : ₱ {{number_format($record->sum('amount_paid'), 2)}}</p>
                </div>

                <div class="flex justify-between" style="margin-top: 35px;">
                    <div>
                        <p class="font-semibold text-md" style="margin-top: 5px;">Cashier : {{strtoupper(auth()->user()->name)}}</p>
                    </div>

                </div>
                @else
                <div class="" style="margin-top: 35px;">
                    <div>
                        <p class="font-semibold text-lg text-center italic" style="margin-top: 5px;">No Transactions Yet</p>
                    </div>

                </div>
                @endif
            {{-- </div> --}}


    </div>
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

        }
    </script>
</x-filament-panels::page>
