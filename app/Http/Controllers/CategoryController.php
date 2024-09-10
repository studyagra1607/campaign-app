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

            $categories = Category::loginUser()->paginate(25);
            
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

            $category = Category::loginUser()->find($id)->update($request->except('user_id'));

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

            $category = Category::loginUser()->find($id);

            $category?->delete();

            return response()->json([
                // 'category' => $category,
                'message' => 'Category deleted successfully!',
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
