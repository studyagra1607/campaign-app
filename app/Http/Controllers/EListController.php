<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Models\EList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return $request->all();
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
