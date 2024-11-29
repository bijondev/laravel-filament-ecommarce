<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Brand;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Title('Products - BijonDEV')]
class ProductsPage extends Component
{
    use WithPagination;
    public function render()
    {
        $products = Product::where('is_active', 1);
        $brands = Brand::where('is_active', 1)->get(['id', 'name', 'slug']);
        $categories = Category::where('is_active', 1)->get(['id', 'name', 'slug']);

        return view('livewire.products-page',[
            'products' => $products->paginate(6),
            'brands'=>$brands,
            'categories'=>$categories
        ]);
    }
}