<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('updateDataToTemplate')) {
    function updateDataToTemplate($data, $template)
    {

        foreach ($data as $key => $value) {
            $template = str_replace('{{ '.$key.' }}', $value, $template);
        }

        return $template;

    }
}

if (! function_exists('saveFile')) {
    function saveFile($request, $folder)
    {

        if ($request->hasFile('file')) {

            $method = strtolower($request->method());

            $file = $request->file('file');
            $old_file = $request->file_path;

            $filename = $request->file->getClientOriginalName();
            $slugname = Str::slug(pathinfo($filename, PATHINFO_FILENAME));
            $folder_id = auth()->id();
            $sub_folder = $folder;
            $file_slug = time().'-'.$slugname.'.'.$file->getClientOriginalExtension();
            $full_filepath = "{$folder_id}/{$sub_folder}/{$file_slug}";

            if (in_array($method, ['post', 'put'])) {
                Storage::disk('local')->put($full_filepath, file_get_contents($file));
            }

            deleteFile($request, $old_file);

            $request->merge([
                'file_name' => $filename,
                'file_slug' => $file_slug,
                'file_path' => $full_filepath,
                'hash' => Str::uuid(),
            ]);

        }

    }
}

if (! function_exists('deleteFile')) {
    function deleteFile($request, $old_file_path)
    {

        $method = strtolower($request->method());

        if (in_array($method, ['put', 'delete'])) {
            if (Storage::disk('local')->exists($old_file_path)) {
                Storage::disk('local')->move($old_file_path, 'delete/'.$old_file_path);
            }
        }

    }
}

if (! function_exists('stringTrim')) {
    function stringTrim($string)
    {

        return preg_replace('/\s+/', ' ', trim($string));

    }
}

if (! function_exists('setBoolean')) {
    function setBoolean($value)
    {

        if (is_string($value)) {
            $value = strtolower(trim($value));
            if ($value === 'false') {
                return false;
            }
            if ($value === 'true') {
                return true;
            }
        }

        return (bool) $value;

    }
}
