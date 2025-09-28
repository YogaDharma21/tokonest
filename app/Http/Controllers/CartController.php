<?php

namespace App\Http\Controllers;

use App\Models\Cart\Cart;
use App\Models\Product\Product;
use App\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function getOrCreateCart()
    {
        $cart = Cart::with(['items', 'address'])->where('user_id', auth()->user()->id)->first();
        if (is_null($cart)) {
            $cart = Cart::create([
                'user_id' => auth()->user()->id,
                'address_id' => optional(auth()->user()->addresses()->where('is_default', 1)->first())->id,
                'courier' => null,
                'courier_type' => null,
                'courier_estimation' => null,
                'courier_price' => 0,
                'voucher_id' => null,
                'voucher_value' => 0,
                'voucher_cashback' => 0,
                'service_fee' => 0,
                'total' => 0,
                'pay_with_coin' => 0,
                'payment_method' => null,
                'total_payment' => 0,
            ]);
            $cart->refresh();
        }
        $cart->total = ($cart->items->sum('total')) + $cart->courier_price + $cart->service_fee - $cart->voucher_value;
        $cart->total_payment = $cart->total - $cart->pay_with_coin;
        $cart->save();

        return $cart;
    }
    public function getCart()
    {
        $cart = $this->getOrCreateCart();

        return ResponseFormatter::success(
            [
                'cart' => $cart->api_response,
                'item' => $cart->items->pluck('api_response')
            ]
        );
    }
    public function addToCart()
    {
        $validator = Validator::make(request()->all(), [
            'product_uuid' => 'required|exists:products,uuid',
            'qty' => 'required|numeric|min:1',
            'note' => 'nullable|string',
            'variations' => 'nullable|array',
            'variations.*.label' => 'required|exists:variations,name',
            'variations.*.value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                400,
                $validator->errors(),
            );
        }

        $cart = $this->getOrCreateCart();

        $product = Product::where('uuid', request()->product_uuid)->firstOrFail();

        if ($product->stock < request()->qty) {
            return ResponseFormatter::error(
                400,
                null,
                ['Stock tidak cukup'],
            );
        }

        if ($cart->items->isNotEmpty() && $cart->items->first()->product->seller_id != $product->seller_id) {
            return ResponseFormatter::error(
                400,
                null,
                ['Keranjang hanya bisa berisi produk dari satu penjual'],
            );
        }

        $cart->items()->create([
            'product_id' => $product->id,
            'variations' => request()->variations,
            'qty' => request()->qty,
            'note' => request()->note,
        ]);

        return $this->getCart();
    }
}
