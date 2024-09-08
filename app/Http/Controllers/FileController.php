<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show($filename)
    {
        $path = $filename;

        if (!auth()->check()) {
            abort(403);
        };
        
        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        };

        $file = Storage::disk('private')->get($path);
        $mimeType = Storage::disk('private')->mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
