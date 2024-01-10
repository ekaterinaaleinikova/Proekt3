<?php 

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function create(Request $request)
    {
        $products = new Product();
        $products->name = $request->name;
        $products->price = $request->price;  
        $products->description = $request->description; 
        $products->save();
        
        return $products;  // Returning the created categories
    }
    public function create_list()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return $products;
    }  

    /*public function update(Request $request, $id)
    {
        $categories = Categories::orderBy('id', 'desc')->get();
        return $categories;
    }*/
    public function delete($id)
    {
        $products = Product::find($id);
        
        if ($products) {
            $products->delete();
            return "Categories with ID $id has been deleted.";
        } 
        else {
            return "Categories with ID $id was not found.";
        }
    }
}

 