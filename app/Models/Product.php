<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    private  static $product, $image, $imageNewName, $directory, $imgUrl;

    public static function saveProduct($request)
    {
        self::$product = new  Product();
        self::$product->name = $request->name;
        self::$product->category_name = $request->category_name;
        self::$product->brand_name = $request->brand_name;
        self::$product->description = $request->description;
        self::$product->image = self::saveImage($request);
        self::$product->save();
    }

    private static  function saveImage($request)
    {
        self::$image = $request->file('image');
        self::$imageNewName = 'category-' . rand() . '.' . self::$image->Extension();
        self::$directory = 'admin-asset/upload-image/porduct/';
        self::$imgUrl = self::$directory . self::$imageNewName;
        self::$image->move(self::$directory, self::$imageNewName);
        return self::$imgUrl;
    }


    public static function updateProduct($request)
    {
        self::$product = Product::find($request->id);
        self::$product->name = $request->name;
        self::$product->category_name = $request->category_name;
        self::$product->brand_name = $request->brand_name;
        self::$product->description = $request->description;
        if ($request->file('image')) {
            if (self::$product->image) {
                if (file_exists(self::$product->image)) {
                    unlink(self::$product->image);
                    self::$product->image = self::saveImage($request);
                }
            } else {
                self::$product->image = self::saveImage($request);
            }
        }
        self::$product->save();
    }

    public static function statusProduct($id)
    {
        self::$product = Product::find($id);
        if (self::$product->status == 1) {
            self::$product->status = 0;
        } else {
            self::$product->status = 1;
        }
        self::$product->save();
    }
    public static function deleteProduct($request)
    {
        self::$product = Product::find($request->id);
        if (self::$product->image) {
            if (file_exists(self::$product->image)) {
                unlink(self::$product->image);
                self::$product->delete();
            }
        } else {
            self::$product->delete();
        }
    }
}
