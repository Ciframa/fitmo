<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Map_table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Categories::select("categories.id", "categories.name", "categories.id_parent", "categories.path", "map_table.path as url_path")->join('map_table', 'categories.id', '=', 'map_table.category_id')
            ->get();
    }

    public function makeMapTable()
    {
        Map_table::truncate();
        $data =  Categories::get();
        $dataToInsert = [];
        foreach ($data as $item) {
            $item["kebabCase"] = Str::ascii(Str::kebab(strtolower(($item["name"]))));
            foreach ($data as $subItem) {
                if ($item["id_parent"] == 0) {
                    $item["url"] .= $item["kebabCase"];
                    $dataToInsert[] = ["path" => $item["url"], "category_id" => $item["id"]];
                    break;
                }
                if ($item["id_parent"] == $subItem["id"]) {
                    $item["url"] .=  $subItem["url"] . "/" . $item["kebabCase"];
                    $dataToInsert[] = ["path" => $item["url"], "category_id" => $item["id"]];
                    break;
                }
            }
        }
        Map_table::insert($dataToInsert);
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

    public function getCategory($categoryName)
    {
        $categoryName = join("/", explode(",", $categoryName));
        $mainCategory = Categories::select("categories.id", 'categories.name', 'map_table.path as url_path')
            ->join('map_table', 'categories.id', '=', 'map_table.category_id')
            ->where('map_table.path', $categoryName)->first();

        $listOfCategories = Categories::select('categories.id', 'categories.name', 'map_table.path as url_path')
            ->join('map_table', 'categories.id', '=', 'map_table.category_id')
            ->where('categories.id_parent', $mainCategory["id"])->get();

        $listOfCategories[] = $mainCategory;

        return $listOfCategories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
