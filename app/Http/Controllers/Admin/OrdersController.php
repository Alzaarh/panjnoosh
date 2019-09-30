<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Http\Resources\Transaction as TransactionResource;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $transactions = Transaction::paginate();
        return TransactionResource::collection($transactions);
    }

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return new TransactionResource($transaction);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'status' => 'required|string|in:1,2,3,4',
        ]);
        $transaction = Transaction::findOrFail($id);
        $order = $transaction->order()->first();
        $order->status = $request->input('status');
        $order->save();
        return response()->json(['message' => 'ok'], 200);
    }
}
