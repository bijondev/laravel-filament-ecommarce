<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;


#[Title('Product Detai - BijonDev')]
class ProductDetailPage extends Component
{
    use LivewireAlert;

    public $slug;
    public $quantaty = 1;

    public function increseQty(){
        $this->quantaty++;
    }

    public function decreseQty(){
        if($this->quantaty>1){
            $this->quantaty--;
        }

    }

    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCartWithQty($product_id, $this->quantaty);

        $this->dispatch('update-cart-count', $total_count)->to(Navbar::class);

        $this->alert('success', 'Product added to the cart successfully!',[
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true
        ]);
    }

    public function mount($slug){
        $this->slug = $slug;
    }
    public function render()
    {
        $product = Product::where('slug',$this->slug)->firstOrFail();
        return view('livewire.product-detail-page', [
            'product' => $product,
    ]);
    }
}
