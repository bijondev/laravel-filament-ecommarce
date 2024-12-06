<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement{

    static public function addItemToCart($product_id){
        $cart_items = self::getCartItemsFromCookie();

        $existing_item = null;

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                break;
            }
        }

        if($existing_item !== null){
            $cart_items[$existing_item]['quantaty']++;
            $cart_items[$existing_item]['total_price'] = $cart_items[$existing_item]['price'];
            $cart_items[$existing_item]['unit_price'];
        }
        else{
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
            if($product){
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'images' => $product->images[0],
                    'quantaty' => 1,
                    'unit_price' => $product->price,
                    'total_price' => $product->price
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    static public function addItemToCartWithQty($product_id, $qty=1){
        $cart_items = self::getCartItemsFromCookie();

        $existing_item = null;

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                break;
            }
        }

        if($existing_item !== null){
            $cart_items[$existing_item]['quantaty']=$qty;
            $cart_items[$existing_item]['unit_price'] = $cart_items[$existing_item]['price'];
            $cart_items[$existing_item]['total_price'];
        }
        else{
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
            if($product){
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'images' => $product->images[0],
                    'quantaty' => $qty,
                    'unit_price' => $product->price,
                    'total_price' => $product->price*$qty
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    static public function removeCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                unset($cart_items[$key]);
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    static public function addCartItemsToCookie($cart_items){
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }

    static public function cleatrCartItems(){
        Cookie::queue(Cookie::forget('cart_items'));
    }

    static public function getCartItemsFromCookie(){
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if(!$cart_items){
            $cart_items = [];
        }

        return $cart_items;
    }

    static public function incrimentQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $cart_items[$key]['quantaty']++;
                $cart_items[$key]['total_price'] = $cart_items[$key]['unit_price']*$cart_items[$key]['quantaty'];

            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    static public function decrimentQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                if($cart_items[$key]['quantaty'] > 1){
                    $cart_items[$key]['quantaty']--;
                    $cart_items[$key]['total_price'] = $cart_items[$key]['unit_price']*$cart_items[$key]['quantaty'];
                }
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    static public function calculateGrandTotal($items){
        return array_sum(array_column($items, 'total_price'));
    }

}
?>