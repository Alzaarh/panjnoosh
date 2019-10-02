<?php

namespace App\Helpers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class Zarinpal
{
    const ZARINPAL_URL =
        'https://www.zarinpal.com/pg/rest/WebGate/PaymentRequest.json';

    const ZARINPAL_FAKE_URL =
        'https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json';

    const ZARINPAL_REDIRECT_URL = 'https://www.zarinpal.com/pg/StartPay/';

    const ZARINPAL_REDIRECT_FAKE_URL =
        'https://sandbox.zarinpal.com/pg/StartPay/';
    const ZARINPAL_VERIFY = 'https://www.zarinpal.com/pg/rest/WebGate/PaymentVerification.json';
    const ZARINPAL_FAKE_VERIFY = 'https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentVerification.json';

    public static function create($totalPrice, $orderId)
    {
        $data = array(
            'MerchantID' => env('MERCHANT_ID'),
            'Amount' => $totalPrice,
            'CallbackURL' => env('CALLBACK_URL'),
            'Description' => 'خرید از پنج‌نوش',
        );
        $jsonData = json_encode($data);
        $ch = curl_init(self::ZARINPAL_FAKE_URL);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        ));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);
        if ($err) {
            return false;
        } else {
            if ($result["Status"] == 100) {
                $transaction;
                $code = $result['Authority'];
                DB::transaction(function () use ($totalPrice, &$transaction, $orderId, $code) {
                    $transaction = Transaction::create([
                        'amount' => $totalPrice,
                        'user_id' => Request::instance()->user->id,
                        'order_id' => $orderId,
                        'code' => $code,
                    ]);
                });
                return [
                    'data' => [
                        'url' =>
                        self::ZARINPAL_REDIRECT_FAKE_URL . $result['Authority'],
                    ],
                ];
            } else {
                return false;
            }
        }
    }

    public static function verify($authority)
    {
        $transaction = Transaction::where('code', $authority)->first();
        if (!$transaction) {
            return response()->json([
                'message' => 'could not verify transaction',
            ], 400);
        }
        $data = array(
            'MerchantID' => env('MERCHANT_ID'),
            'Authority' => $authority,
            'Amount' => $transaction->amount,
        );
        $jsonData = json_encode($data);
        $ch = curl_init(self::ZARINPAL_FAKE_VERIFY);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        ));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if ($err) {
            return response()->json([
                'message' => 'could not verify',
            ], 400);
        } else {
            if ($result['Status'] == 100) {
                foreach ($transaction->order->products as $product) {
                    $product->quantity -= $product->pivot->quantity;
                    $product->save();
                }
                $transaction->is_verified = true;
                $transaction->save();
                return response()->json([
                    'message' => 'ok',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'could not verify',
                ], 400);
            }
        }
    }
}
