<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Models\EList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Str;

class EListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([]);
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
    public function store(ListRequest $request)
    {
        try {

            DB::transaction(function () use ($request) {
                
                if($request->hasFile('file')){
                
                    $file = $request->file('file');
    
                    $slugname = Str::slug($request->name);
                    $folder_id = auth()->id();
                    $sub_folder = 'lists';
                    $filename = $slugname.'-'.time().'.'.$file->getClientOriginalExtension();
                    $full_filepath = "{$folder_id}/{$sub_folder}/{$filename}";
    
                    // Storage::disk('local')->put($full_filepath, file_get_contents($file));
    
                    // $excel = SimpleExcelReader::create($full_filepath);

                };
                
            });

            return response()->json([
                'status' => true,
                'message' => 'List uploaded successfully',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage().$e->getLine(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EList $eList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EList $eList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EList $eList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EList $eList)
    {
        //
    }
}
