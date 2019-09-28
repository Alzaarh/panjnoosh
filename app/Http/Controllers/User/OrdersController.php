<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order as OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Utils\Zarinpal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Req;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $sortBy = $request->query('sortBy');
        $asc = $request->query('asc');
        $desc = $request->query('desc');

        $query = Order::user($request->user->id);

        if ($sortBy && ($asc || $desc) && $sortBy === 'total_price') {
            $query = $query->orderBy('total_price', $asc ? 'asc' : 'desc');
        } elseif ($sortBy && ($asc || $desc) && $sortBy === 'created_at') {
            $query = $query->orderBy('created_at', $asc ? 'asc' : 'desc');
        }

        $orders = $query->paginate();

        return OrderResource::collection($orders);
    }

    public function show(Request $request, $id)
    {
        $order = Order::user($request->user->id)
            ->findOrFail($id);

        $data = [];

        foreach ($order->products as $orderProduct) {
            array_push($data, $orderProduct->pivot);
        }

        $order->orderProducts = $data;

        return new OrderResource($order);
    }

    public function create(Request $request)
    {
        $this->validateCreate($request);
        $result = Order::createOrder($request);
        return response()->json($result, 201);
    }

    private function validateCreate($request)
    {
        $this->validate($request, [
            'user_address_id' => [
                'required',
                Rule::exists('user_addresses', 'id')->where(function ($query) {
                    $query->where('user_id', Req::instance()->user->id);
                }),
            ],
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($request->input('products') as $key) {
            if ((Product::find($key['id']))->quantity < $key['quantity']) {
                throw ValidationException::withMessages([
                    'products' => 'invalid',
                ]);
            }
        }
    }
}