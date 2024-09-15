<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $categories = Category::loginUser()->paginate($request->page_rows);
            
            return response()->json([
                'categories' => $categories,
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {

            $category = Category::createWithLoginUser($request->all());

            return response()->json([
                'category' => $category,
                'message' => 'Category created successfully!',
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {

            $category = Category::loginUser()->find($id);

            return response()->json([
                'category' => $category,
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {
        try {

            $category = Category::loginUser()->find($id);
            $category->update($request->except('user_id'));

            return response()->json([
                'category' => $category,
                'message' => 'Category updated successfully!',
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {

            $ids = explode(",", $id);
            
            $categories = Category::loginUser()->findMany($ids);

            if($categories->isNotEmpty()){
                $categories->each(function ($category) {
                    $category->delete();
                });
                $msg = count($categories) > 1 ? "(" . count($categories) . ") Categories" : "Category";
            }else{
                return response()->json([
                    'message' => 'Category not found!'
                ], 404);
            };

            return response()->json([
                'message' => "$msg deleted successfully!",
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    /**
     * There custom functions =========================================
     */

    public function getAllCategories()
    {
        try {

            $all_categories = [];

            Category::loginUser()->chunk(100, function ($chunk) use (&$all_categories) {
                $all_categories = array_merge($all_categories, $chunk->toArray());
            });
            
            return response()->json([
                'all_categories' => $all_categories,
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }
    
}
