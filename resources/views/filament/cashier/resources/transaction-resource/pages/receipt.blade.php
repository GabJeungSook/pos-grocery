<x-filament-panels::page>
    <div class="flex justify-end">
        <div  class="flex">
            <div class="px-1">
                <x-filament::button  type="button" icon="heroicon-o-printer" class="btn btn-primary w-32" onclick="printDiv('printarea')">Print</x-filament::button>
            </div>
        </div>

    </div>
    <div id="printarea" class="max-w-full bg-white p-4" class="font-mono">
        <div class="text-center mb-4">
            <h2 class="text-2xl font-bold">POS GROCERY</h2>
        </div>
        <div class="flex justify-between" style="margin-top: 2rem;">
            <p class="font-mono "><span class="font-bold ">Transaction No. :</span> {{$transaction->transaction_number}}</p>
            <p class="font-mono "><span class="font-bold">Date:</span> {{Carbon\Carbon::parse($transaction->created_at)->format('F d, Y H:i A')}}</p>
        </div>
        <div class="mt-4" style="margin-top: 10px">
            <table class="w-full" style="border-style: dotted;">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border font-mono" style="border-style: dotted; border-color: black">Item</th>
                        <th class="py-2 px-4 border font-mono" style="border-style: dotted; border-color: black">Quantity</th>
                        <th class="py-2 px-4 border font-mono" style="border-style: dotted; border-color: black">Price</th>
                        <th class="py-2 px-4 border font-mono" style="border-style: dotted; border-color: black">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->transaction_items as $item)
                        <tr>
                            <td class="py-2 px-4 border text-center font-mono" style="border-style: dotted; border-color: black">{{$item->product->name}}</td>
                            <td class="py-2 px-4 border text-center font-mono" style="border-style: dotted; border-color: black">{{$item->quantity}}</td>
                            <td class="py-2 px-4 border text-center font-mono" style="border-style: dotted; border-color: black">₱ {{$item->price}}</td>
                            <td class="py-2 px-4 border text-center font-mono" style="border-style: dotted; border-color: black">₱ {{$item->sub_total}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-end">
            <div class="mt-4" style="margin-top: 5rem; width: 280px;">
                <p class="font-mono flex justify-between"><span class="font-bold">Sub-total: &nbsp; ₱ </span> <span>{{number_format($transaction->sub_total, 2)}}</span></p>
                <p class="font-mono flex justify-between"><span class="font-bold">Tax: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ₱ </span> <span> {{number_format($transaction->tax, 2)}}</span></p>
                <p class="font-mono flex justify-between"><span class="font-bold">Discount: &nbsp;&nbsp;&nbsp;₱
                </span> <span> {{number_format($transaction->discount, 2)}}</span></p>
                <hr class="border-t my-2" style="border-style: dotted; border-color: black">
                <p class="font-mono flex justify-between"><span class="font-bold">Grand Total: ₱ </span> <span> {{number_format($transaction->grand_total, 2)}}</span></p>
                <p class="font-mono flex justify-between"><span class="font-bold">Amount Paid: ₱ </span> <span> {{number_format($transaction->amount_paid, 2)}}</span></p>
                <hr class="border-t my-2" style="border-style: dotted; border-color: black">
                <p class="font-mono flex justify-between"><span class="font-bold">Change: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;₱ </span> <span> {{number_format($transaction->change, 2)}}</span></p>
            </div>
        </div>



        <div class="mt-8 text-center font-bold font-mono" style="margin-top: 8rem;">
            <p>Thank you for your purchase!</p>
        </div>
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
