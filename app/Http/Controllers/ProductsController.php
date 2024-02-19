<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function create(Request $request)
    {
        $products = new Product();
        $products->name = $request->name;
        $products->price = (integer) $request->price;
        $products->description = $request->description;
        $products->category_id = (integer) $request->category_id;
        $photo = $request->file('photo');
        $photoPath = $photo->store('public/image/img');

        $products->image = str_replace('public/image/', 'storage/image/', $photoPath);

        $products->save();

        return $products;

    }
    public function create_list()
    {
        $products = Product::orderBy('id')->get();
        return $products;
    }

    public function delete($id)
    {
        $products = Product::find($id);

        if ($products) {
            $products->delete();
            return "Categories with ID $id has been deleted.";
        } else {
            return "Categories with ID $id was not found.";
        }
    }

    public function productsByCategory($category_id)
    {
        $products = Product::where('category_id', '=', $category_id)->get();
        return $products;
    }

    public function getImage($imageName)
    {
        $imagePath = storage_path('app/public/image/' . $imageName);

        if (file_exists($imagePath)) {
            return response()->file($imagePath)->header('Content-Type', 'image/img');
        } else {
            return response('Image not found', 404);
        }
    }
}


