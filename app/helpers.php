<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

function saveFile($request, $folder)
{
    
    if($request->hasFile('file')){

        $method = strtolower($request->_method);

        $file = $request->file('file');
        $old_file = $request->file_path;

        $slugname = Str::slug($request->name);
        $folder_id = auth()->id();
        $sub_folder = $folder;
        $filename = time().'-'.$slugname.'.'.$file->getClientOriginalExtension();
        $full_filepath = "{$folder_id}/{$sub_folder}/{$filename}";

        if(in_array($method, ['post', 'put'])){
            Storage::disk('local')->put($full_filepath, file_get_contents($file));
        };
        
        if(in_array($method, ['put', 'delete'])){
            if (Storage::disk('local')->exists($old_file)) {
                Storage::disk('local')->move($old_file, 'delete/'.$old_file);
            };
        };
        
        $request->merge([
            'file_name' => $filename,
            'file_path' => $full_filepath,
            'hash' => Str::uuid(),
        ]);

    };
}