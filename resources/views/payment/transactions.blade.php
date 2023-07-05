<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-3 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-visible">
            <div class="p-6 text-gray-900">
                <table class="w-full">
                    <tr class="bg-slate-600 text-white">
                        <th class="w-auto text-center capitalize">Invoice Number</th>
                        <th class="w-auto text-center capitalize">paid amount</th>
                        <th class="w-auto text-center capitalize">Tracking Code</th>
                        <th class="w-auto text-center capitalize">Card</th>
                        <th class="w-auto text-center capitalize">paid card</th>
                        <th class="w-auto text-center capitalize">status</th>
                        <th class="w-auto text-center capitalize">Time</th>
                    </tr>
                    @php
                        $i = 0;
                        $orders = \Illuminate\Support\Facades\Auth::user()->orders;
                    @endphp
                    @foreach($orders as $order)
                        @foreach($order->transactions as $transaction)
                            @php
                                $i++;
                                $i % 2 === 0 ? $bg = 'bg-slate-200' : $bg = 'bg-slate-100';
                            @endphp
                            <tr class="{{$bg}}">
                                <td class="w-auto text-center">{{ $order->id }}</td>
                                <td class="w-auto text-center">{{ $transaction->payment_amount }}</td>
                                <td class="w-auto text-center">{{ $transaction->tracking_code ?? '-' }}</td>
                                <td class="w-auto text-center">
                                    @php $cardNum = $transaction->card->card_number; @endphp
                                    {{
                                    \Illuminate\Support\Str::substr($cardNum, 0, 4) . ' / ' .
                                    \Illuminate\Support\Str::substr($cardNum, 4, 4) . ' / ' .
                                    \Illuminate\Support\Str::substr($cardNum, 8, 4) . ' / ' .
                                    \Illuminate\Support\Str::substr($cardNum, 12, 4)
                                    }}
                                </td>
                                <td class="w-auto text-center">{{ $transaction->paid_card ?? '-' }}</td>
                                <td class="w-auto text-center">
                                    {{ \App\Models\Transaction::getGatewayMessage($transaction->completion_status) }}
                                </td>
                                <td class="w-auto text-center">{{ $transaction->updated_at }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
