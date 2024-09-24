<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show($hash)
    {
        if (! auth()->check()) {
            abort(403);
        }

        $template = Template::hashOfLoginUser($hash)->firstOrFail();

        $path = $template->file_path;

        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        $file = Storage::disk('local')->get($path);
        $mimeType = Storage::disk('local')->mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
