<?php

namespace App\Utils;

class Zarinpal
{
    const ZARINPAL_URL =
        'https://www.zarinpal.com/pg/rest/WebGate/PaymentRequest.json';

    const ZARINPAL_FAKE_URL =
        'https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json';

    const ZARINPAL_REDIRECT_URL = 'https://www.zarinpal.com/pg/StartPay/';

    const ZARINPAL_REDIRECT_FAKE_URL =
        'https://sandbox.zarinpal.com/pg/StartPay/';

    public static function startTransaction($totalPrice)
    {
        $data = array(
            'MerchantID' => env('MERCHANT_ID'),
            'Amount' => $totalPrice,
            'CallbackURL' => 'https://google.com',
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
                return self::ZARINPAL_REDIRECT_FAKE_URL . $result["Authority"];
            } else {
                return false;
            }
        }
    }
}
