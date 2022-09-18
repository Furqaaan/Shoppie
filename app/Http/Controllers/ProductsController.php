<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ProductsController extends Controller
{
    public function addProducts() {
        return view("addProducts");
    }

    public function getProducts() {
        $returnData = [];
        $returnData['products'] = DB::table("products")->get();
        return $returnData;
    }

    public function storeProducts() {
        $newProduct = [
            "Name" => request('name'),
            "Stock" => request("stock"),
            "Price" => request("price"),
            "OriginalStock" => request("stock")
        ];

        $storeNewProduct = DB::table("products")->insert($newProduct);

        return $storeNewProduct;
    }

    public function viewProducts() {

        $products = DB::table("products")->get();

        $totalPrice = 0;
        $cartData = DB::table("products")->join("cart","products.ID","=","cart.ProductID")->get();
        foreach($cartData as $item) {
            $totalPrice += $item->Quantity*$item->Price;
            $item->ItemTotalPrice = $item->Quantity * $item->Price;
        }

        return view("viewProducts",compact("products","cartData","totalPrice"));
    }
}
