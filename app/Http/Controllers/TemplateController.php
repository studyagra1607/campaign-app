<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $templates = Template::loginUser()->paginate(25);
            
            return response()->json([
                'templates' => $templates,
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
    public function store(TemplateRequest $request)
    {
        try {
            
            saveFile($request, 'template');
            
            $template = Template::createWithLoginUser($request->all());

            return response()->json([
                'template' => $template,
                'message' => 'Template added successfully!',
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

            $template = Template::loginUser()->find($id);

            return response()->json([
                'template' => $template,
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
    public function edit(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TemplateRequest $request, $id)
    {
        try {

            saveFile($request, 'template');

            $template = Template::loginUser()->find($id)->update($request->except('user_id'));

            return response()->json([
                'template' => $template,
                'message' => 'Template updated successfully!',
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
            
            $template = Template::loginUser()->find($id);

            $template?->delete();

            return response()->json([
                // 'template' => $template,
                'message' => 'Template deleted successfully!',
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    public function getAllTemplates()
    {
        try {

            $all_templates = [];

            Template::loginUser()->chunk(100, function ($chunk) use (&$all_templates) {
                $all_templates = array_merge($all_templates, $chunk->toArray());
            });
            
            return response()->json([
                'all_templates' => $all_templates,
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
