<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Order_product;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductState;
use App\Models\Template;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Imagick;

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
            'product_states.discount',
            'product_states.topProduct',
            'product_states.newProduct',
            'categories.name as category_name',
            'prices.price',
            'prices.discounted',
            'prices.discountedPercent',
            'colors.color_primary',
            'colors.color_secondary',
            'colors.color_name',
            'products.*',
            'product_categories.category_id as category_id',
            'product_categories.product_order'
        )
            ->with(['categories', 'children' => function ($query) {
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
                    ->where('products.isActive', 1)
                    ->whereNotNull('categories.id_parent');
            }])
            ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
            ->join('product_states', 'products.id', '=', 'product_states.product_id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'categories.id', '=', 'product_categories.category_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
            ->where('products.parent_id', 0)
            ->where('products.isActive', 1)
            ->whereNotNull('categories.id_parent')
            ->groupBy('products.id') // Group by product.id here
            ->orderBy('categories.id', 'asc')
            ->orderBy('categories.id_parent', 'asc')
            ->orderBy('categories.childIndex', 'asc')
            ->orderBy('product_categories.product_order', 'asc')
            ->paginate($request->pageSize, ['*'], 'page', $request->page ?? 1);


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
        $products = Product::select(
            'prices.*',
            'product_states.*',
            'categories.name as category_name',
            'product_categories.category_id as category_id',
            'map_table.path as category_path',
            'colors.*',
            'products.*'
        )
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id) AS image_urls')
            ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
            ->leftJoin('product_states', 'products.id', '=', 'product_states.product_id')
            ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'product_categories.category_id')
            ->leftJoin('map_table', 'product_categories.category_id', '=', 'map_table.category_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->orderBy('products.created_at', "asc")
            ->where("products.url_name", $product_url_name)
            ->where('products.isActive', 1)
            ->groupBy('products.id') // Ensure each product is only once in children
            ->get();
        return $this->formatProducts($products);
    }

    public function getTemplates($product_id)
    {
        $templates = Template::where("product_id", $product_id)->orderBy('sort', "asc")->get();
        return $templates;
    }

    public function getSearchedItems(Request $request, $query)
    {
        $searchedProducts = [];
        if ($query !== 'byPopular') {

            // Explode the search query into individual words
            $searchWords = explode(' ', $query);

            // Loop through each search word
            foreach ($searchWords as $word) {
                // Skip empty words
                if (empty($word)) {
                    continue;
                }

                // Perform the product query for each word
                $searchedItems = Product::select(
                    'products.id',
                )
                    ->where('products.name', 'like', '%' . $word . '%')
                    ->where('products.isActive', 1)
                    ->where('products.parent_id', 0)
                    ->get();
                if (count($searchedItems) > 0) {
                    $searchedProducts[] = $searchedItems;
                }
            }
        } else {
            $searchedItems = Product::select(
                'products.id',
            )
                ->where('products.name', 'like', '%' . 'ball' . '%')
                ->where('products.isActive', 1)
                // need to check
                ->where('products.parent_id', 0)
                ->get();

            if (count($searchedItems) > 0) {
                $searchedProducts[] = $searchedItems;
            }

        }
        $categories = [];
        // if the search query is empty try to search categories
        if (count($searchedProducts) === 0) {
            if ($query !== 'byPopular') {
                // Explode the search query into individual words
                $searchWords = explode(' ', $query);

                // Loop through each search word
                foreach ($searchWords as $word) {
                    // Skip empty words
                    if (empty($word)) {
                        continue;
                    }
                    $categories[] = Category::select('categories.id')
                        ->where('categories.name', 'like', '%' . $word . '%')
                        ->groupBy('categories.id')->get();
                }
            } else {
                $categories = Category::select('categories.id')
                    ->where('categories.name', 'like', '%' . "v" . '%')
                    ->groupBy('categories.id')->get();
            }

            // Take occurrences of categories and sort them
            $flatArray = collect($categories)->flatten(1)->toArray();
            $ids = array_column($flatArray, 'id');

            // Count occurrences
            $idCounts = array_count_values($ids);
            // Sort by occurrences in descending order
            arsort($idCounts);

            $topCategoriesIds = array_slice(array_keys($idCounts), 0, 10);

            // Get categories by ids
            $categories = Category::select('categories.*',
                'map_table.path as category_path',
            )
                ->join('map_table', 'categories.id', '=', 'map_table.category_id')
                ->whereIn('categories.id', $topCategoriesIds)
                ->get();
            // Now i need 6 products from the categories if they are not in first category then search in the second one and etc
            $finalProducts = [];
            foreach ($categories as $category) {
                if (count($finalProducts) < 6) {
                    $products = Product::select(
                        'product_states.discount',
                        'product_states.topProduct',
                        'product_states.newProduct',
                        'prices.price',
                        'prices.discounted',
                        'prices.discountedPercent',
                        'colors.color_primary',
                        'colors.color_secondary',
                        'colors.color_name',
                        'products.*',
                        'product_categories.category_id as category_id',
                    )
                        ->with(['children' => function ($query) {
                            $query->select(
                                'prices.*',
                                'product_states.*',
                                'colors.*',
                                'products.*',
                            )
                                ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
                                ->join('product_states', 'products.id', '=', 'product_states.product_id')
                                ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                                ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                                ->where('products.parent_id', '!=', 0)
                                ->where('products.isActive', 1);
                        }])
                        ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
                        ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                        ->leftJoin('product_states', 'products.id', '=', 'product_states.product_id')
                        ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                        ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                        ->where('products.parent_id', 0)
                        ->where('products.isActive', 1)
                        ->where('product_categories.category_id', $category->id)
                        ->groupBy('products.id')
                        ->limit(6)
                        ->get();

                }
                if (count($products) > 0) {
                    $finalProducts[] = $products;
                }
                $finalProducts = collect($finalProducts)->flatten(1);
            }

        } else {
            $flatArray = collect($searchedProducts)->flatten(1)->toArray();
            $ids = array_column($flatArray, 'id');

            // Count occurrences
            $idCounts = array_count_values($ids);
            // Sort by occurrences in descending order
            arsort($idCounts);
            // Get the top 6 product IDs
            $topProductIds = array_slice(array_keys($idCounts), 0, 6);


            // Fetch the actual product objects in one query
            $finalProducts = Product::select(
                'product_states.discount',
                'product_states.topProduct',
                'product_states.newProduct',
                'prices.price',
                'prices.discounted',
                'prices.discountedPercent',
                'colors.color_primary',
                'colors.color_secondary',
                'colors.color_name',
                'products.*',
            )
                ->with(['children' => function ($query) {
                    $query->select(
                        'prices.*',
                        'product_states.*',
                        'colors.*',
                        'products.*',
                    )
                        ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
                        ->join('product_states', 'products.id', '=', 'product_states.product_id')
                        ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                        ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                        ->where('products.parent_id', '!=', 0)
                        ->where('products.isActive', 1);
                }, 'categories' => function ($query) {
                    $query->select('categories.id', 'categories.name', 'map_table.path')
                        ->join('map_table', 'categories.id', '=', 'map_table.category_id');
                }])
                ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
                ->join('product_states', 'products.id', '=', 'product_states.product_id')
                ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                ->where('products.parent_id', 0)
                ->where('products.isActive', 1)
                ->whereIn('products.id', $topProductIds)
                ->groupBy('products.id')
                ->get();
            $finalProducts = $finalProducts->sortBy(function ($product) use ($topProductIds) {
                return array_search($product->id, $topProductIds);
            })->values(); // Reset the keys


            //here i need to get all categories from products even their children
            $allCategoriesFromProducts = [];
            foreach ($finalProducts as $product) {
                foreach ($product->categories as $category) {
                    array_push($allCategoriesFromProducts, $category);
                }
                foreach ($product->children as $child) {
                    foreach ($child->categories as $category) {
                        $allCategoriesFromProducts[] = $category;
                    }

                }
            }
            // now i need to remove duplicates from the array by id
            $categories = collect($allCategoriesFromProducts)->unique('id')->values()->toArray();
        }

        // If categories are empty and there are no products, search
        if (count($categories) === 0 && count($finalProducts) === 0) {
            return $this->getSearchedItems($request, 'byPopular');
        }

        return [
            'products' => $this->formatProducts($finalProducts, true),
            'categories' => $categories
        ];
    }


    public
    function getAllProducts()
    {
        $assignedCategory = Product::select(
            'categories.name as category_name',
            'colors.*',
            'products.*',
            'product_categories.category_id as category_id'
        )
            ->with(['children' => function ($query) {
                $query->select(
                    'categories.name as category_name',
                    'colors.*',
                    'products.*'
                )
                    ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->leftJoin('categories', 'categories.id', '=', 'product_categories.category_id')
                    ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                    ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                    ->where('products.parent_id', '!=', 0)
                    ->whereNotNull('products.parent_id')
                    ->groupBy('products.id'); // Ensure each product is only once in children
            }])
            ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'product_categories.category_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
            ->where(function ($query) {
                $query->where('products.parent_id', 0)
                    ->orWhereNull('products.parent_id');
            })
            ->groupBy('products.id') // Ensure each product appears only once in the main query
            ->orderBy('products.name', 'asc')
            ->get();

        return $this->formatOnlyPhotos($assignedCategory);
    }

    public
    function getProductsForOrders()
    {
        //Select all products and categories from ProductCategory table, group by category
        $categories = Category::select('categories.*')
            ->with(['products' => function ($query) {
                $query->select(
                    'colors.*',
                    'products.*',
                )
                    ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                    ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                    ->where('products.parent_id', 0)
                    ->whereNotNull('products.parent_id')
                    ->orderBy('product_categories.product_order', 'asc');
            }])
            ->get();

        $categories = $categories->map(function ($category) {
            $category->products = $this->formatOnlyPhotos($category->products);
            return $category;
        });
        return $categories;
    }


    public
    function setUrlNames()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $product->url_name = str_replace(" ", "-", (strtolower(str_replace([","], "", Str::ascii($product["name"])))));
            $product->save();
        }
    }

    public
    function getMainProducts()
    {
        return Product::select('categories.*', 'products.*')->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'categories.id', '=', 'product_categories.category_id')->orderBy("products.name")->where("products.parent_id", 0)->with('categories')
            ->groupBy('products.id') // Ensure each product is only once in children
            ->get();
    }

    public
    function formatProducts($products, $shouldWorkWithChildren = false)
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

    public
    function formatOnlyPhotos($products)
    {
        $nullParentProducts = [];
        $finalProducts = [];
        $categoryNullIds = [];
        foreach ($products as $product) {
            if (isset($product->image_urls)) {
                $product->image_urls = explode('|', $product->image_urls);
            }
            $product->categoryPath = str_replace([","], "", Str::ascii(Str::kebab(strtolower(($product["category_name"])))));

            if ($product->parent_id === null) {
                $nullParentProducts[] = $product;
            } else if ($product->category_id === null) {
                $categoryNullIds[] = $product;
            } else {
                if ($product->children->isNotEmpty()) {
                    foreach ($product->children as $child) {
                        if (isset($product->image_urls)) {
                            if (gettype($child->image_urls) === "array") {
                                //array to string
                                $child->image_urls = implode("|", $child->image_urls);
                            }
                            $child->image_urls = explode('|', $child->image_urls);
                        }
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
        if (!empty($categoryNullIds)) {
            $unassignedCategory = new \stdClass();
            $unassignedCategory->name = "Bez kategorií";
            $unassignedCategory->children = $categoryNullIds;
            // Convert collection to array

            // Insert at the beginning of the array
            array_unshift($finalProducts, $unassignedCategory);

            // Convert back to collection

        }
        return $finalProducts;
    }

    public function getProductByIds($ids)
    {
        $ids = explode(',', $ids); // <-- Add this line

        $products = Product::select(
            'prices.*',
            'product_states.*',
            'colors.*',
            'products.*',
            'categories.name as category_name',
            'categories.id as category_id'
        )
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id) AS image_urls')
            ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
            ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('product_states', 'products.id', '=', 'product_states.product_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->leftJoin('categories', 'categories.id', '=', 'product_categories.category_id')
            ->whereIn('products.id', $ids)
            ->addSelect(Product::raw('(CASE WHEN products.id IN (SELECT parent_id FROM products WHERE parent_id IS NOT NULL) THEN 1 ELSE 0 END) AS hasChildren'))
            ->with('categories', 'images')
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


        $query = Product::select(
            'prices.*',
            'product_states.*',
            'categories.name as category_name',
            'colors.*',
            'categories.*',
            'products.*',
            'product_categories.category_id as category_id',
            'product_categories.product_order'
        )
            ->with(['categories', 'children' => function ($query) use ($request) {
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
                    ->where('products.isActive', 1)
                    ->whereNotNull('categories.id_parent')
                    ->groupBy('products.id');

                // 🔽 Apply same filter logic to children as well
                if ($request->filter) {
                    $query->where(function ($q) use ($request) {
                        $rangeFrom = $request->filter['price']["min"] ?? 0;
                        $rangeTo = $request->filter['price']["max"] ?? PHP_INT_MAX;

                        $q->where(function ($sub) use ($rangeFrom, $rangeTo) {
                            $sub->whereNull('prices.price')
                                ->orWhereBetween('prices.price', [$rangeFrom, $rangeTo]);
                        });

                        $q->where(function ($sub) use ($rangeFrom, $rangeTo) {
                            $sub->whereNull('prices.discounted')
                                ->orWhereBetween('prices.discounted', [$rangeFrom, $rangeTo]);
                        });
                    });
                }
            }])
            ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
            ->join('product_states', 'products.id', '=', 'product_states.product_id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'categories.id', '=', 'product_categories.category_id')
            ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
            ->selectRaw('(SELECT GROUP_CONCAT(image_path SEPARATOR "|") FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
            ->where('products.parent_id', 0)
            ->where('products.isActive', 1)
            ->whereIn('category_id', $categoriesToSearch)
            ->whereNotNull('categories.id_parent')
            ->orderBy('categories.id', 'asc')
            ->orderBy('categories.id_parent', 'asc')
            ->orderBy('categories.childIndex', 'asc')
            ->orderBy('product_categories.product_order', 'asc');
//TODO fix filter

        if ($request->filter) {
            $query->where(function ($q) use ($request) {
                $rangeFrom = $request->filter['and'][0]["value"] ?? 0;
                $rangeTo = $request->filter['and'][1]["value"] ?? PHP_INT_MAX;

                // price check: either null OR between range
                $q->where(function ($sub) use ($rangeFrom, $rangeTo) {
                    $sub->whereNull('prices.price')
                        ->orWhereBetween('prices.price', [$rangeFrom, $rangeTo]);
                });

                // discounted check: either null OR between range
                $q->where(function ($sub) use ($rangeFrom, $rangeTo) {
                    $sub->whereNull('prices.discounted')
                        ->orWhereBetween('prices.discounted', [$rangeFrom, $rangeTo]);
                });
            });
        }
        $paginatedProducts = $query->paginate(16, ['*'], 'page', $request->page ?? 1);
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


    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function storeTemplate(Request $request, $id)
    {

        foreach ($request[0] as $index => $template) {
            if ($template["from"] === "db") {
                $foundTemplate = Template::find($template["id"]);
                $foundTemplate->sort = $index;
                if (isset($template["text"])) {
                    $foundTemplate->text = $template["text"];
                }
                if (isset($template["text2"])) {
                    $foundTemplate->text = $template["text2"];
                }
                if (isset($template["txt1"])) {
                    $foundTemplate->txt1 = $template["txt1"];
                }
                if (isset($template["txt2"])) {
                    $foundTemplate->txt2 = $template["txt2"];
                }
                if (isset($template["txt3"])) {
                    $foundTemplate->txt3 = $template["txt3"];
                }
                if (isset($template["txt4"])) {
                    $foundTemplate->txt4 = $template["txt4"];
                }
                if (isset($template["txt5"])) {
                    $foundTemplate->txt5 = $template["txt5"];
                }
                if (isset($template["txt6"])) {
                    $foundTemplate->txt6 = $template["txt6"];
                }
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

                    $img->setImageCompressionQuality(75);

                    $img->stripImage();

                    $img->writeImage($path . "/" . $photo["image_path"]);
                }

                $newTemplate = new Template();
                $newTemplate->product_id = $request[1]["id"];
                $newTemplate->type = $template["type"];
                $newTemplate->sort = $index;
                $newTemplate->text = $template["text"];
                $newTemplate->text = $template["text2"];
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

    public
    function store(Request $request)
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
        $newProduct->manageStock = (int)filter_var($request->manageStock, FILTER_VALIDATE_BOOLEAN) ?? true;
        $newProduct->stock = $request->stock ?? 0;
        $newProduct->stockInformation = $request->stockInformation ?? $newProduct->manageStock ? "notAllowed" : "onStock";

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

            $img->setImageCompressionQuality(75);

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
    public
    function show($id)
    {
        //
    }

    public
    function deleteTemplate($productId, $templateId)
    {
        $existingProduct = Product::find($productId);
        $existingColor = Color::find($existingProduct->color_id);
        $existingTemplate = Template::find($templateId);

        // Check if there is existing Image where product_id is same as $productId and image_path is same as $existingTemplate->image with number
        // 1-6
        $existingSameImages = Image::where('product_id', $existingProduct["id"])
            ->where(function ($query) use ($existingTemplate) {
                $query->where('image_path', $existingTemplate->image1)
                    ->orWhere('image_path', $existingTemplate->image2)
                    ->orWhere('image_path', $existingTemplate->image3)
                    ->orWhere('image_path', $existingTemplate->image4)
                    ->orWhere('image_path', $existingTemplate->image5)
                    ->orWhere('image_path', $existingTemplate->image6);
            })
            ->get();

        $imagePaths = [
            $existingTemplate->image1,
            $existingTemplate->image2,
            $existingTemplate->image3,
            $existingTemplate->image4,
            $existingTemplate->image5,
            $existingTemplate->image6,
        ];

        $matchingTemplates = Template::where('product_id', $existingTemplate->product_id)
            ->where('id', '!=', $existingTemplate->id)
            ->where(function ($query) use ($imagePaths) {
                foreach ($imagePaths as $imagePath) {
                    $query->orWhere('image1', $imagePath)
                        ->orWhere('image2', $imagePath)
                        ->orWhere('image3', $imagePath)
                        ->orWhere('image4', $imagePath)
                        ->orWhere('image5', $imagePath)
                        ->orWhere('image6', $imagePath);
                }
            })
            ->get();

        if (($existingSameImages->count() + $matchingTemplates->count()) < 1) {
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
        }

        $existingTemplate->delete();;
    }

    public
    function delete($id)
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
        Image::where('product_id', $id)->delete();
        Template::where('product_id', $id)->delete();
        if ($existingProduct) {
            $existingProduct->delete();
            return "Product successfully deleted";
        }
        return $existingProduct;

    }

    public
    function hideProduct($id): string
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
    public
    function edit($id)
    {
        //
    }

    public
    function update(Request $request, $id)
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
        if ($request->parent_id != 0 && $request->parent_id != null) {
            // get productCategories and make them for this product aswell
            ProductCategory::where('product_id', $product->id)->delete();
            $productCategories = ProductCategory::where('product_id', $product->parent_id)->get();
            foreach ($productCategories as $productCategory) {
                $newProductCategories = new ProductCategory();
                $newProductCategories->product_id = $product->id;
                $newProductCategories->category_id = $productCategory->category_id;
                $newProductCategories->save();
            }
        }
        //Get childrens
        $children = Product::where('parent_id', $product->id)->get();
        $productCategoriesOfThisProduct = ProductCategory::where('product_id', $product->id)->get();
        foreach ($children as $child) {
            // get productCategories and make them for this product aswell
            ProductCategory::where('product_id', $child->id)->delete();
            foreach ($productCategoriesOfThisProduct as $productCategory) {
                $newProductCategories = new ProductCategory();
                $newProductCategories->product_id = $child->id;
                $newProductCategories->category_id = $productCategory->category_id;
                $newProductCategories->save();
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


        //rename folder
        if ($product->name != $request->tempName) {
            //rename all childrens / parents
            if ($request->parent_id != 0) {
                $parents = Product::where('id', $request->parent_id)->get();
                foreach ($parents as $parent) {
                    $folderPath = 'products/';
                    //rename their folders
                    if (isset($parent->color_id)) {
                        $existingColor = Color::find($parent->color_id);
                        $folderPath .= $parent->name . "-" . $existingColor->color_name;
                    } elseif (isset($parent->variant)) {
                        $folderPath .= $parent->name . "-" . $parent->variant;
                    } else {
                        $folderPath .= $parent->name;
                    }
                    if (File::exists($folderPath) && File::isDirectory($folderPath)) {
                        $path = 'products/';

                        if (isset($existingColor)) {
                            $path .= $request->name . "-" . $existingColor->color_name;
                        } elseif (isset($parent->variant)) {
                            $path .= $request->name . "-" . $parent->variant;
                        } else {
                            $path .= $request->name;
                        }

                        rename($folderPath, $path);
                    }
                    $parent->name = $request->name;
                    $parent->save();

                    //rename their children
                    $children = Product::where('parent_id', $parent->id)->get();
                    foreach ($children as $child) {
                        $folderPath = 'products/';
                        if (isset($child->color_id)) {
                            $existingColor = Color::find($child->color_id);
                            $folderPath .= $child->name . "-" . $existingColor->color_name;
                        } elseif (isset($child->variant)) {
                            $folderPath .= $child->name . "-" . $child->variant;
                        } else {
                            $folderPath .= $child->name;
                        }
                        if (File::exists($folderPath) && File::isDirectory($folderPath)) {
                            $path = 'products/';

                            if (isset($existingColor)) {
                                $path .= $request->name . "-" . $existingColor->color_name;
                            } elseif (isset($child->variant)) {
                                $path .= $request->name . "-" . $child->variant;
                            } else {
                                $path .= $request->name;
                            }

                            rename($folderPath, $path);
                        }


                        $child->name = $request->name;
                        $child->save();
                    }
                }
            } else {
                //rename itself
                $folderPath = 'products/';

                if (isset($existingColor)) {
                    $folderPath .= $productOriginalName . "-" . $existingColor->color_name;
                } elseif (isset($productOriginalVariant)) {
                    $folderPath .= $productOriginalName . "-" . $productOriginalVariant;
                } else {
                    $folderPath .= $productOriginalName;
                }
                if (File::exists($folderPath) && File::isDirectory($folderPath)) {
                    $path = 'products/';

                    if (isset($request->color_name)) {
                        $path .= $request->name . "-" . $request->color_name;
                    } elseif (isset($request->variant)) {
                        $path .= $request->name . "-" . $request->variant;
                    } else {
                        $path .= $request->name;
                    }

                    rename($folderPath, $path);
                }

                //rename its children
                $children = Product::where('parent_id', $product->id)->get();
                foreach ($children as $child) {
                    $folderPath = 'products/';
                    if (isset($child->color_id)) {
                        $existingColor = Color::find($child->color_id);
                        $folderPath .= $child->name . "-" . $existingColor->color_name;
                    } elseif (isset($child->variant)) {
                        $folderPath .= $child->name . "-" . $child->variant;
                    } else {
                        $folderPath .= $child->name;
                    }
                    if (File::exists($folderPath) && File::isDirectory($folderPath)) {
                        $path = 'products/';

                        if (isset($existingColor)) {
                            $path .= $request->name . "-" . $existingColor->color_name;
                        } elseif (isset($child->variant)) {
                            $path .= $request->name . "-" . $child->variant;
                        } else {
                            $path .= $request->name;
                        }

                        rename($folderPath, $path);
                    }


                    $child->name = $request->name;
                    $child->save();
                }
            }
        }
        foreach ($request->photos as $photo) {
            if ($photo["state"] === 'added') {
                $folderPath = 'products/';
                if (isset($product->color_id)) {
                    $existingColor = Color::find($product->color_id);
                    $folderPath .= $product->name . "-" . $existingColor->color_name;
                } elseif (isset($product->variant)) {
                    $folderPath .= $product->name . "-" . $product->variant;
                } else {
                    $folderPath .= $product->name;
                }
                if (File::exists($folderPath) && File::isDirectory($folderPath)) {
                    $newImg = new Image();
                    $newImg->product_id = $product->id;
                    $newImg->image_path = $photo["image_path"];
                    $newImg->is_main = (int)filter_var($photo["isMain"], FILTER_VALIDATE_BOOLEAN);

                    $newImg->save();

                    $img = new Imagick();

                    $img->readImage($photo["file"]);

                    $img->setImageCompression(Imagick::COMPRESSION_JPEG);

                    $img->setImageCompressionQuality(75);

                    $img->stripImage();

                    $img->writeImage($folderPath . "/" . $photo["image_path"]);
                } else {
                    File::makeDirectory($folderPath);

                    $newImg = new Image();
                    $newImg->product_id = $product->id;
                    $newImg->image_path = $photo["image_path"];
                    $newImg->is_main = (int)filter_var($photo["isMain"], FILTER_VALIDATE_BOOLEAN);

                    $newImg->save();

                    $img = new Imagick();

                    $img->readImage($photo["file"]);

                    $img->setImageCompression(Imagick::COMPRESSION_JPEG);

                    $img->setImageCompressionQuality(75);

                    $img->stripImage();

                    $img->writeImage($folderPath . "/" . $photo["image_path"]);
                }

            } else if ($photo["state"] === "toDeleteFromDb") {
                $existingImage = Image::where('image_path', $photo["image_path"])
                    ->where('product_id', $product->id)
                    ->first();
                $existingImage->delete();
                $folderPath = 'products/';
                $imagePath = $photo["image_path"];

                $existingSameImages = Image::where('image_path', $photo["image_path"])
                    ->where('product_id', $product->id)
                    ->get();

                $existingSameImagesInTemplates = Template::where('product_id', $product->id)
                    ->where(function ($query) use ($imagePath) {
                        $query->where('image1', $imagePath)
                            ->orWhere('image2', $imagePath)
                            ->orWhere('image3', $imagePath)
                            ->orWhere('image4', $imagePath)
                            ->orWhere('image5', $imagePath)
                            ->orWhere('image6', $imagePath);
                    })->get();

                if (($existingSameImages->count() + $existingSameImagesInTemplates->count()) < 1) {


                    if (isset($product->color_id)) {
                        $existingColor = Color::find($product->color_id);
                        $folderPath .= $product->name . "-" . $existingColor->color_name;
                    } elseif (isset($product->variant)) {
                        $folderPath .= $product->name . "-" . $product->variant;
                    } else {
                        $folderPath .= $product->name;
                    }
                    if (File::exists($folderPath) && File::isDirectory($folderPath)) {
                        $folderPath .= "/";
                        File::delete($folderPath . $photo["image_path"]);
                    }
                }
            } // just update
            else {
                $existingImage = Image::where('image_path', $photo["image_path"])
                    ->where('product_id', $product->id)
                    ->first();
                if ($existingImage) {
                    $existingImage->is_main = (int)filter_var($photo["isMain"], FILTER_VALIDATE_BOOLEAN);
                    $existingImage->save();
                }
            }
        }
//         if ($request->parent["category_id"] != 0) {
//
//             $product->category_id = $request->parent["category_id"];
//             Product::where('parent_id', $product->id)->update(['category_id' => $request->parent["category_id"]]);
//         }
        $this->setUrlNames();

        $product->manageStock = (int)filter_var($request->manageStock, FILTER_VALIDATE_BOOLEAN) ?? true;
        $product->stock = $request->stock ?? 0;
        $product->stockInformation = $request->stockInformation ?? $product->manageStock ? "notAllowed" : "onStock";
        return $product->save();
    }

    public
    function updateOrderOfProducts(Request $request)
    {
        foreach ($request->categories as $category) {
            foreach ($category["products"] as $product) {
                $existingProductCategory = ProductCategory::where('product_id', $product["id"])->where('category_id', $category["id"])->first();
                $existingProductCategory->product_order = $product["order"];
                $existingProductCategory->save();
            }
        }
        return "Products successfully updated";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
