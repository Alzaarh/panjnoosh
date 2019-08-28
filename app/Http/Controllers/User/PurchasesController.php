<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Purchase as PurchaseResource;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Utils\Zarinpal;

class PurchasesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        return PurchaseResource::collection(Purchase::where('user_id', $request->user->id)
                ->with('products')
                ->paginate());
    }
    public function show(Request $request, $id)
    {
        $purchase = Purchase::where('id', $id)
            ->where('user_id', $request->user->id)
            ->with('products')
            ->first();
        if (!$purchase) {
            throw new ModelNotFoundException();
        }
        return new PurchaseResource($purchase);
    }
    public function create(Request $request)
    {
        $input = $this->validateCreate($request);
        $products = [];
        foreach ($input['products'] as $product) {
            $result = Product::where('id', $product['productID'])
                ->where('quantity', '>=', $product['quantity'])
                ->first();
            if (!$result) {
                throw ValidationException::withMessages([
                    'request' => 'درخواست نامعتبر',
                ]);
            }
            array_push($products, $result);
        }
        $result = Zarinpal::purchase();
        if (!$result) {
            return response()->json(['errors' => ['zarinpal' => 'سرویس پاسخگو نمی‌باشد']], 400);
        }
        return response()->json(['data' => ['zarinpalURL' => $result]], 200);
    }
    private function validateCreate(Request $request)
    {
        return $this->validate($request, [
            'products' => 'required|array|min:1',
            'products.*.productID' => 'required|integer|min:1',
            'products.*.quantity' => 'required|integer|min:1',
        ], ['products.*' => 'اطلاعات نامعتبر']);
    }
}
