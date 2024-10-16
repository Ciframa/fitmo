<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Image;
use App\Models\Category;
use App\Models\Color;
use App\Models\Price;
use App\Models\ProductCategory;
use App\Models\Order_product;
use App\Models\Template;
use App\Models\ProductState;
use Illuminate\Http\Request;
use File;
use Imagick;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $paginatedProducts = Product::select(
            'prices.*',
            'product_states.*',
            'categories.name as category_name',
            'colors.*',
            'products.*',
            'product_categories.category_id as category_id'
        )->with(['categories', 'children' => function ($query) {
            $query->select(
                'prices.*',
                'product_states.*',
                'categories.name as category_name',
                'colors.*',
                'products.*',
                'product_categories.category_id as category_id'
            )
                ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
                ->join('product_states', 'products.id', '=', 'product_states.product_id')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->join('categories', 'categories.id', '=', 'product_categories.category_id')
                ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                ->where('products.parent_id', '!=', 0)
                ->where('products.isActive', 1);

        }])
            ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
            ->join('product_states', 'products.id', '=', 'product_states.product_id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'categories.id', '=', 'product_categories.category_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
            ->orderBy('products.created_at', 'desc')
            ->where('products.parent_id', 0)
            ->where('products.isActive', 1)
            ->paginate(20, ['*'], 'page', $request->page ?? 1);


        $products = $this->formatProducts($paginatedProducts, true);

        return response()->json([
            'data' => $products,
            'pagination' => [
                'total' => $paginatedProducts->total(),
                'per_page' => $paginatedProducts->perPage(),
                'current_page' => $paginatedProducts->currentPage(),
                'last_page' => $paginatedProducts->lastPage(),
                'next_page_url' => $paginatedProducts->nextPageUrl(),
                'prev_page_url' => $paginatedProducts->previousPageUrl(),
                'path' => $paginatedProducts->path(),
            ]
        ]);
    }

    public function getSingleProduct($product_url_name)
    {
        $product = Product::select('prices.*', 'product_states.*', 'categories.name as category_name', 'product_categories.category_id as category_id', 'map_table.path as category_path', 'colors.*', 'products.*')
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id) AS image_urls')
            ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
            ->join('product_states', 'products.id', '=', 'product_states.product_id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'categories.id', '=', 'product_categories.category_id')
            ->join('map_table', 'product_categories.category_id', '=', 'map_table.category_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->orderBy('products.created_at', "asc")
            ->where("products.url_name", $product_url_name)
            ->where('products.isActive', 1)
            ->get();

        return $this->formatProducts($product);
    }

    public function getTemplates($product_id)
    {
        $templates = Template::where("product_id", $product_id)->orderBy('sort', "asc")->get();
        return $templates;
    }

    public function getSearchedItems($query)
    {
        // Explode the search query into individual words
        $searchWords = explode(' ', $query);

        // Initialize an array to store matched products
        $matchedProducts = [];

        // Loop through each search word
        foreach ($searchWords as $word) {
            // Skip empty words
            if (empty($word)) {
                continue;
            }

            // Perform the product query for each word
            $products = Product::select('prices.*', 'product_states.*', 'categories.name as category_name', 'product_categories.category_id as category_id', 'map_table.path as category_path', 'colors.*', 'products.*')
                ->with('categories')
                ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
                ->join('product_states', 'products.id', '=', 'product_states.product_id')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->join('categories', 'categories.id', '=', 'product_categories.category_id')
                ->join('map_table', 'product_categories.category_id', '=', 'map_table.category_id')
                ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                ->orderBy('products.created_at', 'desc')
                ->where('products.isActive', 1)
                ->where('products.name', 'like', '%' . $word . '%')
                ->get();

            // Merge the products into the matchedProducts array
            foreach ($products as $product) {
                $matchedProducts[$product->id] = $product; // Use product id as key to avoid duplicates
            }
        }

        // Convert array of matched products back to a collection
        $matchedProducts = collect(array_values($matchedProducts));

        // Return formatted products
        return $this->formatProducts($matchedProducts);
    }


    public function getAllProducts()
    {
        $products = Product::select(
//                     'prices.*',
//                     'product_states.*',
            'categories.name as category_name',
            'colors.*',
            'products.*',
            'product_categories.category_id as category_id'
        )->with(['children' => function ($query) {
            $query->select(
//                         'prices.*',
//                         'product_states.*',
                'categories.name as category_name',
                'colors.*',
                'products.*',
//                         'product_categories.category_id as category_id'
            )
//                         ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
//                         ->join('product_states', 'products.id', '=', 'product_states.product_id')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->join('categories', 'categories.id', '=', 'product_categories.category_id')
                ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                ->where('products.parent_id', '!=', 0)
                ->whereNotNull('products.parent_id');
        }])
//                     ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
//                     ->join('product_states', 'products.id', '=', 'product_states.product_id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'categories.id', '=', 'product_categories.category_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
            ->orderBy('products.name', 'asc')
            ->where(function ($query) {
                $query->where('products.parent_id', 0)
                    ->orWhereNull('products.parent_id');
            })->get();

        return $this->formatOnlyPhotos($products);
    }


    public function setUrlNames()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $product->url_name = str_replace(" ", "-", (strtolower(str_replace([","], "", Str::ascii($product["name"])))));
            $product->save();
        }
    }

    public function getMainProducts()
    {
        return Product::select('categories.*', 'products.*')->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'categories.id', '=', 'product_categories.category_id')->orderBy("products.name")->where("products.parent_id", 0)->with('categories')->get();
    }

    public function formatProducts($products, $shouldWorkWithChildren = false)
    {
        foreach ($products as $product) {
            $product->image_urls = explode('|', $product->image_urls);
            $product->categoryPath = str_replace([","], "", Str::ascii(Str::kebab(strtolower(($product["category_name"])))));
        }
        $newProducts = [];
        foreach ($products as $product) {
            $temp = [];
            $product->isMain = 0;
            if ($product["parent_id"] === 0) {
                $product->isMain = 1;
                $temp[] = $product;

                foreach ($products as $tempProduct) {
                    if ($product["id"] === $tempProduct["parent_id"]) {
                        $temp[] = $tempProduct;
                    }
                }
                if ($shouldWorkWithChildren && $product->children->isNotEmpty()) {
                    foreach ($product->children as $child) {
                        $child->image_urls = explode('|', $child->image_urls);
                        $child->categoryPath = str_replace([","], "", Str::ascii(Str::kebab(strtolower(($child["category_name"])))));
                        $child->isMain = 0; // Activate the product
                        $temp[] = $child;
                    }
                }
                $newProducts[] = $temp;
            }
        }

        return $newProducts;
    }

    public function formatOnlyPhotos($products)
    {
        $nullParentProducts = [];
        $finalProducts = [];
        foreach ($products as $product) {
            $product->image_urls = explode('|', $product->image_urls);
            $product->categoryPath = str_replace([","], "", Str::ascii(Str::kebab(strtolower(($product["category_name"])))));

            if ($product->parent_id === null) {
                $nullParentProducts[] = $product;

            } else {


                if ($product->children->isNotEmpty()) {
                    foreach ($product->children as $child) {
                        $child->image_urls = explode('|', $child->image_urls);
                        $child->categoryPath = str_replace([","], "", Str::ascii(Str::kebab(strtolower(($child["category_name"])))));
                    }
                }
                $finalProducts[] = $product;
            }
        }
        if (!empty($nullParentProducts)) {
            $unassignedCategory = new \stdClass();
            $unassignedCategory->name = "Nezařazeny";
            $unassignedCategory->children = $nullParentProducts;
            // Convert collection to array

            // Insert at the beginning of the array
            array_unshift($finalProducts, $unassignedCategory);

            // Convert back to collection

        }
        return $finalProducts;
    }

    public function getProductById($id)
    {


        // Convert the values to integers if needed


        $products = Product::select(
            'prices.*',
            'product_states.*',
            'colors.*',
            'products.*',

        )
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id) AS image_urls')
            ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('product_states', 'products.id', '=', 'product_states.product_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->where('products.id', $id)
            ->addSelect(Product::raw('(CASE WHEN products.id IN (SELECT parent_id FROM products WHERE parent_id IS NOT NULL) THEN 1 ELSE 0 END) AS hasChildren'))
            ->with('categories')
            ->get();

        foreach ($products as $product) {
            $product->image_urls = explode('|', $product->image_urls);
            $product->categoryPath = str_replace([","], "", Str::ascii(Str::kebab(strtolower(($product["category_name"])))));
        }
        return $products;
    }


    public function getCategoryProducts($categoryName, Request $request)
    {
        $categoryName = join("/", explode(",", $categoryName));
        $products = [];
        $categories = Category::where(
            'path',
            'LIKE',
            Category::select("categories.path")
                ->join('map_table', 'categories.id', '=', 'map_table.category_id')
                ->where('map_table.path', $categoryName)->first()["path"] . "%")->get();

        $categoriesToSearch = [];


        foreach ($categories as $category) {
            $categoriesToSearch[] = $category["id"];
        }


        $paginatedProducts = Product::select(
            'prices.*',
            'product_states.*',
            'categories.name as category_name',
            'colors.*',
            'products.*',
            'product_categories.category_id as category_id'
        )->with(['categories', 'children' => function ($query) {
            $query->select(
                'prices.*',
                'product_states.*',
                'categories.name as category_name',
                'colors.*',
                'products.*',
                'product_categories.category_id as category_id'
            )
                ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
                ->join('product_states', 'products.id', '=', 'product_states.product_id')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->join('categories', 'categories.id', '=', 'product_categories.category_id')
                ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                ->where('products.parent_id', '!=', 0)
                ->where('products.isActive', 1);
        }])
            ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
            ->join('product_states', 'products.id', '=', 'product_states.product_id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'categories.id', '=', 'product_categories.category_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
            ->orderBy('products.created_at', 'desc')
            ->where('products.parent_id', 0)
            ->where('products.isActive', 1)
            ->whereIn('category_id', $categoriesToSearch)
            ->paginate(6, ['*'], 'page', $request->page ?? 1);


        $products = $this->formatProducts($paginatedProducts, true);
        return response()->json([
            'data' => $products,
            'pagination' => [
                'total' => $paginatedProducts->total(),
                'per_page' => $paginatedProducts->perPage(),
                'current_page' => $paginatedProducts->currentPage(),
                'last_page' => $paginatedProducts->lastPage(),
                'next_page_url' => $paginatedProducts->nextPageUrl(),
                'prev_page_url' => $paginatedProducts->previousPageUrl(),
                'path' => $paginatedProducts->path(),
            ]
        ]);

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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeTemplate(Request $request, $id)
    {

        foreach ($request[0] as $index => $template) {
            if ($template["from"] === "db") {
                $foundTemplate = Template::find($template["id"]);
                $foundTemplate->sort = $index;
                $foundTemplate->save();
            };
            if ($template["from"] && $template["from"] === "created") {

                $path = 'products/';
                if (isset($request[1]["color_name"])) {
                    $path .= $request[1]["name"] . "-" . $request[1]["color_name"];
                } elseif (isset($request[1]["variant"])) {
                    $path .= $request[1]["name"] . "-" . $request[1]["variant"];
                } else {
                    $path .= $request[1]["name"];
                }
                $images = [];
                for ($i = 0; $i < 6; $i++) {
                    if ($template["image" . ($i + 1)]) {
                        $images[] = $template["image" . ($i + 1)];
                    };
                }
                foreach ($images as $photo) {
                    $img = new Imagick();

                    $img->readImage($photo["file"]);

                    $img->setImageCompression(Imagick::COMPRESSION_JPEG);

                    $img->setImageCompressionQuality(85);

                    $img->stripImage();

                    $img->writeImage($path . "/" . $photo["image_path"]);
                }

                $newTemplate = new Template();
                $newTemplate->product_id = $request[1]["id"];
                $newTemplate->type = $template["type"];
                $newTemplate->sort = $index;
                $newTemplate->text = $template["text"];
                $newTemplate->image1 = $template["image1"]["image_path"] ?? null;
                $newTemplate->image2 = $template["image2"]["image_path"] ?? null;
                $newTemplate->image3 = $template["image3"]["image_path"] ?? null;
                $newTemplate->image4 = $template["image4"]["image_path"] ?? null;
                $newTemplate->image5 = $template["image5"]["image_path"] ?? null;
                $newTemplate->image6 = $template["image6"]["image_path"] ?? null;
                $newTemplate->txt1 = $template["txt1"] ?? null;
                $newTemplate->txt2 = $template["txt2"] ?? null;
                $newTemplate->txt3 = $template["txt3"] ?? null;
                $newTemplate->txt4 = $template["txt4"] ?? null;
                $newTemplate->txt5 = $template["txt5"] ?? null;
                $newTemplate->txt6 = $template["txt6"] ?? null;
                $newTemplate->subtxt1 = $template["subtxt1"] ?? null;
                $newTemplate->subtxt2 = $template["subtxt2"] ?? null;
                $newTemplate->subtxt3 = $template["subtxt3"] ?? null;
                $newTemplate->subtxt4 = $template["subtxt4"] ?? null;
                $newTemplate->subtxt5 = $template["subtxt5"] ?? null;
                $newTemplate->subtxt6 = $template["subtxt6"] ?? null;
                $newTemplate->color = $template["color"] ?? null;

                $newTemplate->save();
            };
        }

        return "Asi se to povedlo";
    }

    public function store(Request $request)
    {

        if ($request->colors["colorName"]) {
            $newColor = new Color();
            $newColor->color_primary = $request->colors["primary"];
            if ($request->colors["secondary"] ?? null) {
                $newColor->color_secondary = $request->colors["secondary"];
            }
            $newColor->color_name = $request->colors["colorName"];
            $newColor->save();
        }
        $newProduct = new Product();
        $newProduct->name = $request->name;
        $newProduct->description = $request->subName;
        $newProduct->variant = $request->variant;

        $newProduct->parent_id = $request->parent["id"];
        $newProduct->url_name = str_replace(" ", "-", (strtolower(str_replace([","], "", Str::ascii($request["name"])))));
        if ($request->colors["colorName"]) {
            $newProduct->color_id = $newColor->id;
        }
        $newProduct->save();


        if (isset($request->parent["category_id"]) && $request->parent["category_id"] == 0) {
            foreach ($request->categories as $category) {
                $newProductCategories = new ProductCategory();
                $newProductCategories->product_id = $newProduct->id;
                $newProductCategories->category_id = $category["categoryId"];
                $newProductCategories->save();
            }

        } else {
            foreach ($request->parent["categories"] as $category) {
                $newProductCategories = new ProductCategory();
                $newProductCategories->product_id = $newProduct->id;
                $newProductCategories->category_id = $category["id"];
                $newProductCategories->save();
            }
        }

        $path = 'products/';

        if (isset($request->colors["colorName"])) {
            $path .= $request->name . "-" . $request->colors["colorName"];
        } elseif (isset($request->variant)) {
            $path .= $request->name . "-" . $request->variant;
        } else {
            $path .= $request->name;
        }


        File::makeDirectory($path);
        $newState = new ProductState();
        $newState->product_id = $newProduct->id;
        $newState->discount = (int)filter_var($request->stateButtons["discount"], FILTER_VALIDATE_BOOLEAN);
        $newState->newProduct = (int)filter_var($request->stateButtons["newProduct"], FILTER_VALIDATE_BOOLEAN);
        $newState->topProduct = (int)filter_var($request->stateButtons["topProduct"], FILTER_VALIDATE_BOOLEAN);
        $newState->save();

        if ($request->prices["normal"]) {

            $newPrice = new Price();
            $newPrice->product_id = $newProduct->id;
            $newPrice->price = $request->prices["normal"];
            $newPrice->discounted = $request->prices["discounted"] ?? null;
            $newPrice->discountedPercent = $request->prices["discountedPercent"] ?? null;
            $newPrice->save();
        }


        foreach ($request->photos as $photo) {
            $newImg = new Image();
            $newImg->product_id = $newProduct->id;
            $newImg->image_path = $photo["image_path"];
            $newImg->is_main = (int)filter_var($photo["isMain"], FILTER_VALIDATE_BOOLEAN);

            $newImg->save();

            $img = new Imagick();

            $img->readImage($photo["file"]);

            $img->setImageCompression(Imagick::COMPRESSION_JPEG);

            $img->setImageCompressionQuality(85);

            $img->stripImage();

            $img->writeImage($path . "/" . $photo["image_path"]);
        }


        $this->setUrlNames();
        return $newProduct;
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

    public function deleteTemplate($productId, $templateId)
    {
        $existingProduct = Product::find($productId);
        $existingColor = Color::find($existingProduct->color_id);
        $existingTemplate = Template::find($templateId);

        $folderPath = 'products/';

        if (isset($existingColor)) {
            $folderPath .= $existingProduct["name"] . "-" . $existingColor["colorName"];
        } elseif (isset($existingProduct["variant"])) {
            $folderPath .= $existingProduct["name"] . "-" . $existingProduct["variant"];
        } else {
            $folderPath .= $existingProduct["name"];
        }
        $imageFiles = glob($folderPath . '/*.jpg');
        $folderPath .= "/";
        foreach ($imageFiles as $imageFile) {
            if ($imageFile == $folderPath . $existingTemplate->image1 || $imageFile == $folderPath . $existingTemplate->image2 || $imageFile == $folderPath . $existingTemplate->image3 || $imageFile == $folderPath . $existingTemplate->image4 || $imageFile == $folderPath . $existingTemplate->image5 || $imageFile == $folderPath . $existingTemplate->image6) {
                File::delete($imageFile);
            }

        }
        $existingTemplate->delete();;
    }

    public function delete($id)
    {
        $orderProducts = Order_product::where('product_id', $id)->get();
        if ($orderProducts->isNotEmpty()) {
            // return error message
            return response()->json(['error' => 'Produkt nelze smazat, protože je součástí objednávky.'], 400);
        }
        $existingProduct = Product::find($id);
        //Find products where parent_id is the id of the product we want to delete and set parent_id to null and isActive to 0
        $children = Product::where('parent_id', $id)->get();
        foreach ($children as $child) {
            $child->parent_id = null;
            $child->isActive = 0;
            $child->save();
        }

        $existingColor = Color::find($existingProduct->color_id);
        $folderPath = 'products/';

        if (isset($existingColor)) {
            $folderPath .= $existingProduct["name"] . "-" . $existingColor["colorName"];
        } elseif (isset($existingProduct["variant"])) {
            $folderPath .= $existingProduct["name"] . "-" . $existingProduct["variant"];
        } else {
            $folderPath .= $existingProduct["name"];
        }


        if ($existingColor) {
            $existingColor->delete();
        }

        if (File::exists($folderPath) && File::isDirectory($folderPath)) {
            // Folder exists, delete it
            if (File::deleteDirectory($folderPath)) {
                echo "Folder deleted successfully.";
            } else {
                echo "Unable to delete the folder.";
            }
        } else {
            echo "Folder does not exist.";
        }
        ProductCategory::where('product_id', $id)->delete();
        Price::where('product_id', $id)->delete();
        Images::where('product_id', $id)->delete();
        Template::where('product_id', $id)->delete();
        if ($existingProduct) {
            $existingProduct->delete();
            return "Product successfully deleted";
        }
        return $existingProduct;

    }

    public function hideProduct($id): string
    {

        $existingProduct = Product::find($id);

        if ($existingProduct) {
            $existingProduct->isActive = $existingProduct->isActive ? 0 : 1;
            $existingProduct->save();
            return "Product successfully updated";
        } else {
            return "Product not found";
        }
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
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $productOriginalName = $product->name;
        $productOriginalVariant = $product->variant;
        $product->name = $request->name;
        $product->description = $request->description;
        // $product->category_id = $request->category_id;
        foreach ($request->categories as $category) {
            $productCategory = ProductCategory::where('product_id', $product->id)->where('category_id', $category["id"] ?? $category["categoryId"])->first();
            if (isset($category["categoryStatus"])) {

                if (!$productCategory && $category["categoryStatus"] == "added") {
                    $newProductCategories = new ProductCategory();
                    $newProductCategories->product_id = $product->id;
                    $newProductCategories->category_id = $category["id"] ?? $category["categoryId"];
                    $newProductCategories->save();
                }
                if ($productCategory && $category["categoryStatus"] == "deleted") {
                    $productCategory->delete();
                }
            }
        }
        $product->parent_id = $request->parent_id;

        $product->variant = $request->variant;

        if ($product->color_id) {
            $existingColor = Color::find($product->color_id);
            $originalColorName = $existingColor->name;
            if (!$request->color_name) {
                $existingColor->delete();
            } else {
                $existingColor->color_primary = $request->color_primary;
                if ($request->colors["secondary"] ?? null) {
                    $existingColor->color_secondary = $request->colors["secondary"];
                }
                $existingColor->color_name = $request->color_name;
                $existingColor->save();
            }
        } else {
            if (!$request->color_name) {
                $newColor = new Color();
                $newColor->color_primary = $request->color_primary;
                if ($request->color_secondary ?? null) {
                    $newColor->color_secondary = $request->color_secondary;
                }
                $newColor->color_name = $request->color_name;
                $newColor->save();
            }
            $existingPrice = Price::where("product_id", $product->id)->first();

            if ($existingPrice) {
                $existingPrice->price = $request->price;
                $existingPrice->discounted = $request->discounted ?? null;;
                $existingPrice->discountedPercent = $request->discountedPercent ?? null;
                $existingPrice->save();
            } else {
                if ($request->price) {

                    $newPrice = new Price();
                    $newPrice->product_id = $product->id;
                    $newPrice->price = $request->price;
                    $newPrice->discounted = $request->discounted ?? null;;
                    $newPrice->discountedPercent = $request->discountedPercent ?? null;
                    $newPrice->save();
                }
            }
        }
        $existingStates = ProductState::where("product_id", $product->id)->first();
        if ($existingStates) {
            $existingStates->discount = (int)filter_var($request->discount, FILTER_VALIDATE_BOOLEAN);
            $existingStates->newProduct = (int)filter_var($request->newProduct, FILTER_VALIDATE_BOOLEAN);
            $existingStates->topProduct = (int)filter_var($request->topProduct, FILTER_VALIDATE_BOOLEAN);
            $existingStates->save();
        } else {
            $newState = new ProductState();
            $newState->product_id = $product->id;
            $newState->discount = (int)filter_var($request->discount, FILTER_VALIDATE_BOOLEAN);
            $newState->newProduct = (int)filter_var($request->newProduct, FILTER_VALIDATE_BOOLEAN);
            $newState->topProduct = (int)filter_var($request->topProduct, FILTER_VALIDATE_BOOLEAN);
            $newState->save();
        }
        $folderPath = 'products/';

        if (isset($existingColor)) {
            $folderPath .= $productOriginalName . "-" . $originalColorName;
        } elseif (isset($productOriginalVariant)) {
            $folderPath .= $productOriginalName . "-" . $productOriginalVariant;
        } else {
            $folderPath .= $productOriginalName;
        }
        if (File::exists($folderPath) && File::isDirectory($folderPath)) {
            $path = 'products/';

            if (isset($request->color_name)) {
                ;
                $path .= $request->name . "-" . $request->color_name;
            } elseif (isset($request->variant)) {
                $path .= $request->name . "-" . $request->variant;
            } else {
                $path .= $request->name;
            }

            rename($folderPath, $path);
        }
//         if ($request->parent["category_id"] != 0) {
//
//             $product->category_id = $request->parent["category_id"];
//             Product::where('parent_id', $product->id)->update(['category_id' => $request->parent["category_id"]]);
//         }
        $this->setUrlNames();
        return $product->save();
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
}
