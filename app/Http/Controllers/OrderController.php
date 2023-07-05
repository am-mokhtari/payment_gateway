<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('payment.transactions');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Order $order)
    {
        if ($order->status == 'paid') {
            $transaction = $order->lastTransaction;
            if (is_null($transaction)) {
                return redirect()->route('dashboard')->with('success', 'Your Order Paid.');
            }
            return redirect()->route('receipt', $transaction->id);
        } else {
            $existTransaction = $order->lastTransaction;
            if (!is_null($existTransaction)) {
                if (is_null($existTransaction->completion_status)) {
                    date_default_timezone_set('UTC');
                    $now = strtotime('now');
                    $created_at = strtotime($existTransaction->created_at);

                    if (($now - $created_at) < 600) {
                        if ($existTransaction->card_id == $request->all()["card_id"]) {
                            return view('payment.invoice', ['transaction' => $existTransaction]);
                        }
                    }
                    $existTransaction->delete();
                }
            }

            return $this->createPayRequest($order, $request->all()["card_id"]);
        }
    }

    /**
     * @throws RequestException
     */
    private function createPayRequest(Order $order, string $card_id)
    {
        $id = $order->id;
        $amount = $order->amount;
        $callback = 'http://' . env('DB_HOST') . ':8000/pay/result';
        $sign = hash_hmac('sha512', $amount . '#' . $id . '#' . $callback, env('SECRET_KEY'));
        $body = [
            'amount' => $amount,
            'order_id' => $id,
            'callback' => $callback,
            'sign' => $sign,
            'callback_method' => 1
        ];

        $httpRequest = Http::acceptJson()
            ->withToken(env('GATEWAY_ID'))
            ->withBody(json_encode($body), 'application/json')
            ->timeout(20)
            ->connectTimeout(5)
            ->retry(2, 300)
            ->post('https://core.paystar.ir/api/pardakht/create');

        $response = $httpRequest->json();

        if ($response['status'] == 1) {
            $transaction = Transaction::create([
                'token' => $response['data']['token'],
                'ref_num' => $response['data']['ref_num'],
                'order_id' => $response['data']['order_id'],
                'payment_amount' => $response['data']['payment_amount'],
                'card_id' => $card_id,
            ]);
            return view('payment.invoice', ['transaction' => $transaction]);
        } else {
            return back()->with('danger', Transaction::getGatewayMessage($response['status']));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @throws RequestException
     */
    public function store(Request $request)
    {
        $response = $request->all();

        $transaction = Transaction::where('ref_num', '=', $response['ref_num'])->latest()->first();
        $transaction->transaction_id = $response['transaction_id'];
        $transaction->save();

        if ($response['status'] == '1') {

            $transaction->paid_card = $response['card_number'];
            $transaction->tracking_code = $response['tracking_code'];
            $transaction->save();

            $amount = $transaction->order->amount;
            $ref_num = $transaction->ref_num;
            $cardNumber = Str::mask($transaction->card->card_number, '*', -10, 6);
            $tracking_code = $transaction->tracking_code;
            $sign = hash_hmac('sha512', $amount . '#' . $ref_num . '#' . $cardNumber . '#' . $tracking_code, env('SECRET_KEY'));
            $body = [
                'ref_num' => $ref_num,
                'amount' => $amount,
                'sign' => $sign
            ];

            $httpRequest = Http::acceptJson()
                ->withToken(env('GATEWAY_ID'))
                ->withBody(json_encode($body), 'application/json')
                ->timeout(20)
                ->connectTimeout(5)
                ->retry(2, 300)
                ->post('https://core.paystar.ir/api/pardakht/verify');

            $httpResponse = $httpRequest->json();

            $transaction->completion_status = $httpResponse['status'];
            $transaction->save();
            if ($httpResponse['status'] == 1) {
                $order = $transaction->order;
                $order->status = 'paid';
                $order->save();
                return redirect()->route('receipt', $transaction->id)->with('success', Transaction::getGatewayMessage($httpResponse['status']));
            } else {
                return redirect()->route('dashboard')->with('danger', 'خطا در اعتبارسنجی:' . Transaction::getGatewayMessage($httpResponse['status']) . ' ### code: ' . $response['ref_num']);
            }
        } else {
            $transaction->completion_status = $response['status'];
            $transaction->save();
            return redirect()->route('dashboard')->with('warning', 'خطا در هنگام پرداخت:' . Transaction::getGatewayMessage($response['status']) . ' ### code: ' . $response['ref_num']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return view('payment.receipt', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
//
