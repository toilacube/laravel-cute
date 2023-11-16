<?php

namespace App\Services\Products;

use PDO;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\ProductItemImage;

use function PHPSTORM_META\map;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsService
{

    public function getCasualProducts($category)
    {
        if (!is_null($category)) {
            if ($category == 'ao-cac-loai') $category = 2;
            else if ($category == 'quan-cac-loai') $category = 3;
            else if ($category == 'phu-kien-cac-loai') $category = 4;
            else $category = -1;
        }
        $parent_category_id = DB::table('product_category')
            ->where('category_name', 'Mặc hàng ngày')
            ->value('id');

        // return an array of category_id
        $category_id = DB::table('product_category')
            ->where('parent_category_id', $parent_category_id)
            ->when($category != -1, function ($query) use ($category) {
                return $query->where('id', $category);
            })
            ->get('id')
            ->pluck('id');


        $casual = DB::table('product')
            ->whereIn('category_id', $category_id)
            ->get();

        $casual = $casual->map(function ($product) {

            // get list of product color name
            $item = DB::table('product_item')
                ->where('product_id', $product->id)
                ->distinct()
                ->get('color');
            $id =  $product->id;

            $item = $item->map(function ($c) use ($id) {

                //get the correspoding color image
                $colorImage = DB::table('product_item')
                    ->where('product_id', $id)
                    ->where('color', $c->color)
                    ->get(['color_image', 'id', 'qty_in_stock'])
                    ->first();

                // get the item image
                $itemImage = DB::table('product_item_image')
                    ->where('product_item_id', $colorImage->id)
                    ->get('url');
                $c->color_img = $colorImage->color_image;
                $c->item_img = $itemImage;
                $c->qtycube = $colorImage->qty_in_stock;
                return $c;
            });

            $product->item = $item;
            return $product;
        });

        // The number of items per page
        $perPage = 10; // You can change this value to the number of items you want per page.

        // Create a LengthAwarePaginator instance
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
        $pagedData = array_slice($casual->toArray(), ($page - 1) * $perPage, $perPage);
        $casualPaginated = new LengthAwarePaginator($pagedData, count($casual), $perPage);

        return response($casualPaginated);

        //return response()->json($casual);
    }


    public function getTest()
    {
        $items = ProductItem::find(1);
        $items->product_item_images()->get();

        $itemsWithImages = ProductItem::with('product_item_images')->get();
        $productsWithItemsAndImages = Product::with('product_items.product_item_images')->get();

        $items = ProductItemImage::find(1);
        $itemsImg = $items->product_item()->get();

        return response($itemsImg);
    }
}
