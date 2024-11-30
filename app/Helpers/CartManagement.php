<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;

class CartManagement{

    static public function addCartItemsToCookie($cart_items){
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }

    static public function cleatcartItems(){
        Cookie::queue(Cookie::forget('cart_items'));
    }

}
?>