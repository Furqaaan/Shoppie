<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CartController extends Controller
{

    public function getCart() {
        return 1; 
    }

    public function addToCart() {
        $product = request("product");
        $qty = request("qty");
        $stock = request("stock");

        if($qty > $stock) {
            return -1;
        }

        $cartItem = DB::table("cart")->where("ProductID",$product)->first();
        $productItem = DB::table("products")->where("ID",$product)->first();

        if(empty($cartItem)){

            $newCartItem = [
                "ProductID" => $product,
                "Quantity" => $qty,
            ];

            DB::table("cart")->insert($newCartItem);
            DB::table("products")->where("ID",$product)->update([
                "Stock" => $productItem->Stock - $qty
            ]);

        }else {
              DB::table("cart")->where("ProductID",$product)->update([
                "Quantity" => $cartItem->Quantity + $qty
              ]);

              DB::table("products")->where("ID",$product)->update([
                "Stock" => $productItem->Stock - $qty
            ]);
        }

        $totalPrice = 0;
        $cartData = DB::table("products")->join("cart","products.ID","=","cart.ProductID")->get();
        foreach($cartData as $item) {
            $totalPrice += $item->Quantity*$item->Price;
            $item->ItemTotalPrice = $item->Quantity * $item->Price;
        }

        $returnData = [];
        $returnData['products'] = DB::table("products")->get();
        $returnData['cartData'] = $cartData;
        $returnData['totalPrice'] = $totalPrice;

        return $returnData;

    }

    public function deleteCartItem() {

        $productId = request("product");
        $cartId = request("cart");

        $product = DB::table("products")->where("ID",$productId)->first();

        DB::table("cart")->where("ID",$cartId)->delete();

        DB::table("products")->where("ID",$productId)->update([
            "Stock" => $product->OriginalStock
        ]);

        $totalPrice = 0;
        $cartData = DB::table("products")->join("cart","products.ID","=","cart.ProductID")->get();
        foreach($cartData as $item) {
            $totalPrice += $item->Quantity*$item->Price;
        }

        return $totalPrice;
    }
}
