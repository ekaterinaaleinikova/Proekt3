<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $categories = new Categories();
        $categories->name = $request->input('name');
        $photo = $request->file('photo');
        $photoPath = $photo->store('public/image/img');
        $categories->image = str_replace('public/image/', 'storage/image/', $photoPath);


        $categories->save();

        return $categories;
    }


    public function list()
    {
        $categories = Categories::orderBy('id', 'desc')->get();
        return $categories;
    }

    public function delete($id)
    {
        $categories = Categories::find($id);

        if ($categories) {
            Storage::delete($categories->image);

            $categories->delete();
            return "Categories with ID $id has been deleted.";
        } else {
            return "Categories with ID $id was not found.";
        }
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
