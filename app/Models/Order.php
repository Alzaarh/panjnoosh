<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Order extends Model
{
    protected $fillable = [
        'total_price',
        'user_id',
        'user_city',
        'user_state',
        'user_address',
        'user_zipcode',
        'user_phone',
        'user_receiver_name',
        'status',
    ];

    public const STATUS = [
        'pending' => '0',
        'preparation' => '1',
        'sent' => '2',
        'delivered' => '3',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function products()
    {
        return $this->belongsToMany(\App\Models\Product::class)
            ->withPivot('product_title', 'product_price', 'quantity');
    }

    public static function createOrder(Request $request)
    {
        $data = [];

        $data['total_price'] = 0;

        $productData = [];

        foreach ($request->input('products') as $product) {
            $p = \App\Models\Product::find($product['id']);

            $price = $p->price * ($p->off > 0 ? $p->off : 100) / 100;

            $data['total_price'] += $price;

            array_push($productData, [
                'product_price' => $price,
                'product_id' => $p->id,
                'product_title' => $p->title,
                'quantity' => $product['quantity'],
            ]);
        }

        $data['user_id'] = $request->user->id;

        $address = \App\Models\UserAddress::find(
            $request->input('user_address_id')
        );

        $data['user_city'] = $address->city;

        $data['user_state'] = $address->state;

        $data['user_address'] = $address->address;

        $data['user_zipcode'] = $address->zipcode;

        $data['user_phone'] = $address->phone;

        $data['user_receiver_name'] = $address->receiver_name;

        $data['status'] = '0';

        $order = self::create($data);

        $order->products()->attach($productData);

        $order->orderProducts = $productData;

        return $order;
    }
}
