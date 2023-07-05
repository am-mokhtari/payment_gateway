<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Receipt') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-3 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-visible">
            <div class="p-6 text-gray-900">
                <table class="w-full">
                    <tr class="bg-slate-600 text-white">
                        <th class="w-1/5 text-center capitalize">Invoice Number</th>
                        <th class="w-1/5 text-center capitalize">Order Amount</th>
                        <th class="w-1/5 text-center capitalize">Tracking Code</th>
                        <th class="w-1/5 text-center capitalize">Card</th>
                        <th class="w-1/5 text-center capitalize">Time</th>
                    </tr>
                    <tr>
                        <td class="w-1/5 text-center capitalize">{{ $transaction->order_id }}</td>
                        <td class="w-1/5 text-center capitalize">{{ $transaction->payment_amount }}</td>
                        <td class="w-1/5 text-center capitalize">{{ $transaction->tracking_code }}</td>
                        <td class="w-1/5 text-center capitalize">
                            @php $cardNum = $transaction->card->card_number; @endphp
                            {{
                            \Illuminate\Support\Str::substr($cardNum, 0, 4) . ' / ' .
                            \Illuminate\Support\Str::substr($cardNum, 4, 4) . ' / ' .
                            \Illuminate\Support\Str::substr($cardNum, 8, 4) . ' / ' .
                            \Illuminate\Support\Str::substr($cardNum, 12, 4)
                            }}
                        </td>
                        <td class="w-1/5 text-center capitalize">{{ $transaction->updated_at }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
