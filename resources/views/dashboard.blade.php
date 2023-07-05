<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="w-full capitalize">
                        <a href="{{ route('transactions') }}">
                            <button class="capitalize bg-sky-600 hover:bg-sky-800 text-white
                                            py-1 px-3 my-1 rounded">
                                {{ __('see all transactions') }}
                            </button>
                        </a>
                    </div>
                    <table class="w-full mb-6">
                        <tr class="bg-slate-600 text-white">
                            <th class="w-1/4 text-center">Invoice Number</th>
                            <th class="w-1/4 text-center">Amount</th>
                            <th class="w-1/4 text-center">Status</th>
                            <th class="w-1/4 text-center">Operate</th>
                        </tr>
                        @php $i = 0; @endphp
                        @foreach(\Illuminate\Support\Facades\Auth::user()->orders as $order)
                            @php
                                $i++;
                                $i % 2 === 0 ? $bg = 'bg-slate-200' : $bg = 'bg-slate-100';
                            @endphp
                            <tr class="{{$bg}}">
                                <td class="w-1/4 text-center">{{ __($order->id) }}</td>
                                <td class="w-1/4 text-center">{{ __($order->amount) }}</td>
                                <td class="w-1/4 text-center">{{ __($order->status) }}</td>
                                <td class="w-1/4 text-center">
                                    @if($order->status === 'paid')
                                        <a href="{{route('pay', $order->id)}}">
                                            <button class="capitalize bg-sky-600 hover:bg-sky-800 text-white
                                            py-1 px-3 my-1 rounded">
                                                see details
                                            </button>
                                        </a>
                                    @else
                                        <button type="button" class="capitalize bg-slate-500 hover:bg-slate-700
                                             text-white py-1 px-3 my-1 rounded" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{$i}}">
                                            pay
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$i}}" tabindex="-1"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title text-black fs-5 capitalize"
                                                            id="exampleModalLabel">choose a card</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                 height="16" fill="currentColor"
                                                                 class="bi bi-x-octagon-fill text-red-700"
                                                                 viewBox="0 0 16 16">
                                                                <path
                                                                    d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('pay', $order->id) }}" method="get">
                                                        <div class="modal-body">
                                                            <div class="w-full my-5 relative border-none">
                                                                <select name="card_id"
                                                                        class="bg-slate-200 focus:bg-gray-300 font-bold text-center appearance-none border-none inline-block py-3 rounded leading-tight w-full">
                                                                    @foreach(\Illuminate\Support\Facades\Auth::user()->cardNumbers as $card)

                                                                        <option class="pt-6"
                                                                                value="{{$card->id}}">{{
                                                                        \Illuminate\Support\Str::substr($card->card_number, 0, 4) . ' / ' .
                                                                        \Illuminate\Support\Str::substr($card->card_number, 4, 4) . ' / ' .
                                                                        \Illuminate\Support\Str::substr($card->card_number, 8, 4) . ' / ' .
                                                                        \Illuminate\Support\Str::substr($card->card_number, 12, 4)
                                                                    }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div
                                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                                                    <i class="fas fa-chevron-down text-gray-600"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                    class="bg-slate-400 px-3 py-2 rounded text-white capitalize hover:shadow-lg
                                                                hover:shadow-slate-500/50 hover:bg-slate-500"
                                                                    data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <button type="submit"
                                                                    class="bg-sky-500 px-3 py-2 rounded text-white capitalize hover:shadow-lg
                                                                    hover:shadow-cyan-500/50 hover:bg-teal-500">
                                                                see invoice & pay
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
