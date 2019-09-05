<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order as OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

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
}
