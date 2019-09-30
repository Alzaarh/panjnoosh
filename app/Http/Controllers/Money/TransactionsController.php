<?php

namespace App\Http\Controllers\Money;

use App\Helpers\Zarinpal;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as ReqFacade;

class TransactionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'create',
        ]]);
    }

    public function create(Request $request)
    {
        $this->validateCreate($request);
        $order = Order::find($request->input('order_id'));
        $totalPrice = 0;
        foreach ($order->products as $product) {
            if ($product->quantity < $product->pivot->quantity) {
                return response()->json(['message' => 'bad request'], 400);
            }
            $totalPrice += $product->pivot->quantity * $product->pivot->product_price;
        }
        $result = Zarinpal::create($totalPrice, $request->input('order_id'));
        if (!$result) {
            return response()->json([
                'message' => 'unable to make transaction',
            ], 500);
        }
        return response()->json($result, 201);
    }

    public function verify(Request $request)
    {
        return Zarinpal::verify($request->input('Authority'));
    }

    private function validateCreate($request)
    {
        $this->validate($request, [
            'order_id' => [
                'required',
                'integer',
                function ($attr, $value, $fail) {
                    $order = Order::where('id', $value)
                        ->where('user_id', ReqFacade::instance()->user->id)
                        ->first();
                    if (!$order) {
                        $fail($attr . ' is invalid');
                    }
                    $transactionForOrder = Transaction::where('order_id', $value)->first();
                    if ($transactionForOrder) {
                        $fail($attr . ' already has a transaction');
                    }
                },
            ],
        ]);
    }
}
