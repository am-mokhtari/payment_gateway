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
                        <th class="w-1/5 text-center capitalize">Invoice Number</th>
                        <th class="w-1/5 text-center capitalize">Order Amount</th>
                        <th class="w-1/5 text-center capitalize">Commission</th>
                        <th class="w-1/5 text-center capitalize">Final Amount</th>
                        <th class="w-1/5 text-center capitalize">Status</th>
                    </tr>
                    @php
                        $order = $transaction->order;
                    @endphp
                    <tr>
                        <td class="w-1/5 text-center">{{ $order->id }}</td>
                        <td class="w-1/5 text-center">{{ $order->amount }}</td>
                        <td class="w-1/5 text-center">{{ $transaction->payment_amount - $order->amount }}</td>
                        <td class="w-1/5 text-center font-bold">{{ $transaction->payment_amount }}</td>
                        <td class="w-1/5 text-center">{{ __($order->status) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-center font-bold">
                            @php $cardNum = $transaction->card->card_number; @endphp
                            {{
                            \Illuminate\Support\Str::substr($cardNum, 0, 4) . ' / ' .
                            \Illuminate\Support\Str::substr($cardNum, 4, 4) . ' / ' .
                            \Illuminate\Support\Str::substr($cardNum, 8, 4) . ' / ' .
                            \Illuminate\Support\Str::substr($cardNum, 12, 4)
                            }}
                        </td>
                        <td>
                            <form action="https://core.paystar.ir/api/pardakht/payment" method="post">
                                <input type="hidden" name="token" value="{{ $transaction->token }}">
                                <div class="flex items-center justify-center">
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-lime-600 text-white py-1 px-3 rounded">
                                        Pay
                                    </button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="flex items-center justify-center">
                                <a href="{{ route('dashboard') }}">
                                    <button type="button"
                                            class="bg-amber-500 hover:bg-orange-600 text-white py-1 px-3 my-1 rounded">
                                        Cancel
                                    </button>
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
