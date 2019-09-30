<?php

namespace App\Models;

use App\Models\Product;
use App\Models\UserAddress;
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
        'code',
    ];

    public const STATUS = [
        'pending' => '0',
        'preparation' => '1',
        'sent' => '2',
        'delivered' => '3',
    ];

    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function products()
    {
        return $this->belongsToMany(\App\Models\Product::class)
            ->withPivot('product_price', 'quantity');
    }

    public static function createOrder(Request $request)
    {
        $data = [];
        $productData = [];

        foreach ($request->input('products') as $key) {
            $product = Product::find($key['id']);
            array_push($productData, [
                'product_price' => $product->price,
                'product_id' => $product->id,
                'quantity' => $key['quantity'],
            ]);
        }
        $data['user_id'] = $request->user->id;
        $address = UserAddress::find($request->input('user_address_id'));
        $data['user_city'] = $address->city;
        $data['user_state'] = $address->state;
        $data['user_address'] = $address->address;
        $data['user_zipcode'] = $address->zipcode;
        $data['user_phone'] = $address->phone;
        $data['user_receiver_name'] = $address->receiver_name;
        $data['status'] = '0';
        $data['code'] = str_replace('.', '', microtime(true));
        $order = self::create($data);
        $order->products()->attach($productData);
        $order->orderProducts = $productData;
        return [
            'data' => [
                'order' => $order,
            ],
        ];
    }
}
