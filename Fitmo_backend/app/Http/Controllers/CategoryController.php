<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Map_table;
use App\Models\Product;
use App\Models\ProductCategory;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Imagick;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Category::select(
            "categories.id",
            "categories.name",
            "categories.id_parent",
            "categories.path",
            "categories.childIndex",
            "map_table.path as url_path",
            "image_path"
        )
            ->join('map_table', 'categories.id', '=', 'map_table.category_id')
            ->orderBy('categories.id_parent', 'asc')
            ->orderBy('categories.childIndex', 'asc')
            ->orderBy('categories.id', 'asc');

// Apply filters dynamically if they exist
        if ($request->filter) {
            foreach ($request->filter as $condition) {
                $query->where($condition['column'], $condition['operator'], $condition['value']);
            }
        }

// Finalize the query and get the results
        return $query->get();
    }

    public function remap()
    {
        $newCategoriesProducts = [];
        $products = Product::get();
        foreach ($products as $product) {
            $newCategoriesProducts[] = [
                "category_id" => $product->category_id,
                "product_id" => $product->id
            ];
        }
        ProductCategory::insert(array_values($newCategoriesProducts));
        return $newCategoriesProducts;
    }


    public function getMainCategories()
    {
        return Category::select(
            "categories.id",
            "categories.name",
            "categories.id_parent",
            "image_path",
            "map_table.path as url_path"
        )->join('map_table', 'categories.id', '=', 'map_table.category_id')
            ->where("categories.id_parent", 0)
            ->get();
    }

    public function getSubCategory($id)
    {
        return Category::select(
            "categories.id",
            "categories.name",
            "categories.id_parent",
            "image_path"
        )
            ->where("categories.id_parent", $id)
            ->get();
    }

    public function makeMapTable()
    {
        $data = Category::get();
        $dataToInsert = [];

        foreach ($data as $item) {
            $item["kebabCase"] = Str::ascii(Str::kebab(strtolower(($item["name"]))));
            foreach ($data as $subItem) {
                if ($item["id_parent"] == 0) {
                    $item["url"] .= $item["kebabCase"];
                    $dataToInsert[$item["id"]] = ["path" => $item["url"], "category_id" => $item["id"]];
                    break;
                }
                if ($item["id_parent"] == $subItem["id"]) {
                    $item["url"] .= $subItem["url"] . "/" . $item["kebabCase"];
                    $dataToInsert[$item["id"]] = ["path" => $item["url"], "category_id" => $item["id"]];
                    break;
                }
            }
        }

        // Fetch existing records from the Map table
        $existingRecords = Map_table::whereIn('category_id', $data->pluck('id'))->get();

        // Update existing records and remove them from the insert data
        foreach ($existingRecords as $existingRecord) {
            $categoryId = $existingRecord->category_id;
            if (isset($dataToInsert[$categoryId])) {
                $existingRecord->update($dataToInsert[$categoryId]);
                unset($dataToInsert[$categoryId]);
            }
        }

        // Insert new records
        Map_table::insert(array_values($dataToInsert));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getMaxId()
    {
        return Category::max('id');
    }

    public function updateCategories(Request $categories)
    {
        $alphabet = range('A', 'Z');
        foreach ($categories[0] as $category) {
            $categoryFromDb = Category::find($category["id"]);
            if (!$categoryFromDb) {
                $parentCategory = Category::find($category["id_parent"]);
                $categoryFromDb = new Category();
                $categoryFromDb["id"] = $category["id"];
                $categoryFromDb["id_parent"] = $category["id_parent"];
                $categoryFromDb["name"] = $category["name"];
                $categoryFromDb["childIndex"] = $category["index"];
                $categoryFromDb["path"] = isset($parentCategory["path"]) ? $parentCategory["path"] . $alphabet[$category["index"]] : $alphabet[$category["index"]];
                if (isset($category["image"]) && $category["image"]) {
                    $path = 'categories/';
                    if ($categoryFromDb["image_path"]) {
                        File::delete($path . $category["image_path"]);
                    }

                    $categoryFromDb["image_path"] = $category["image"]["image_path"];

                    $img = new Imagick();
                    $img->readImage($category["image"]["file"]);

                    $img->setImageCompression(Imagick::COMPRESSION_JPEG);

                    $img->setImageCompressionQuality(75);

                    $img->stripImage();

                    $img->writeImage($path . "/" . $category['image']["image_path"]);
                }
            } else {
                $parentCategory = Category::find($category["id_parent"]);
                $categoryFromDb["id_parent"] = $category["id_parent"];
                $categoryFromDb["name"] = $category["name"];
                $categoryFromDb["childIndex"] = $category["index"];
                $categoryFromDb["path"] = isset($parentCategory["path"]) ? $parentCategory["path"] . $alphabet[$category["index"]] : $alphabet[$category["index"]];

                if (isset($category["image"]) && $category["image"]) {
                    $path = 'categories/';
                    if ($categoryFromDb["image_path"]) {
                        File::delete($path . $category["image_path"]);
                    }

                    $categoryFromDb["image_path"] = $category["image"]["image_path"];

                    $img = new Imagick();
                    $img->readImage($category["image"]["file"]);

                    $img->setImageCompression(Imagick::COMPRESSION_JPEG);

                    $img->setImageCompressionQuality(75);

                    $img->stripImage();

                    $img->writeImage($path . "/" . $category['image']["image_path"]);
                }
            }

            $categoryFromDb->save();
        }
        $this->makeMapTable();
        return $categories;
    }

    public function getCategory($categoryName)
    {
        $categoryName = join("/", explode(",", $categoryName));
        $mainCategory = Category::select("categories.id", 'categories.name', 'map_table.path as url_path', 'categories.image_path')
            ->join('map_table', 'categories.id', '=', 'map_table.category_id')
            ->where('map_table.path', $categoryName)->first();

        if (!$mainCategory) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $listOfCategories = Category::select('categories.id', 'categories.name', 'map_table.path as url_path', 'categories.image_path')
            ->join('map_table', 'categories.id', '=', 'map_table.category_id')
            ->where('categories.id_parent', $mainCategory["id"])->get();

        $listOfCategories[] = $mainCategory;

        return $listOfCategories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $category["name"] = $request["name"];
        $category["id_parent"] = $request["id_parent"];
        if ($request->image) {
            $path = 'categories/';
            if ($category["image_path"]) {
                File::delete($path . $category["image_path"]);
            }

            $category["image_path"] = $request->image["image_path"];

            $img = new Imagick();
            $img->readImage($request->image["file"]);

            $img->setImageCompression(Imagick::COMPRESSION_JPEG);

            $img->setImageCompressionQuality(75);

            $img->stripImage();

            $img->writeImage($path . "/" . $request['image']["image_path"]);
        }

        $category->save();
        $this->makeMapTable();
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete($id)
    {
        $subCategories = Category::where('id_parent', $id)->get();
        $existingCategory = Category::find($id);
        if (!$existingCategory) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        if ($existingCategory) {
            $existingCategory->delete();
        }
        // Get all subcategories
        // Set the parent id of all subcategories to null
        if ($subCategories->count() === 0) {
            $this->makeMapTable();
            return $id;
        }
        $subCategories->each(function ($subCategory) {
            $subCategory->id_parent = null;
            $subCategory->save();
        });

        $this->makeMapTable();

        return $id;
    }
}
