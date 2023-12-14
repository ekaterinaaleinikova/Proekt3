<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function create(Request $request)
    {
        $categories = new Categories();
        $categories->name = $request->name;
        $categories->save();
        
        return $categories; // Returning the created categories
    }
    
    public function list()
    {
        $categories = Categories::orderBy('id', 'desc')->get();
        return $categories;
    }    
    
    /*public function update(Request $request, $id)
    {
        $categories = Categories::orderBy('id', 'desc')->get();
        return $categories;
    }*/
    public function delete($id)
    {
        $categories = Categories::find($id);
        
        if ($categories) {
            $categories->delete();
            return "Categories with ID $id has been deleted.";
        } 
        else {
            return "Categories with ID $id was not found.";
        }
    }
}
