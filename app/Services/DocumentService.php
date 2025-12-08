<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class DocumentService
{
    public static function upload(UploadedFile $file, $folder = 'documents')
    {
        $name = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs($folder, $name, 'public');

        return [
            'file_name' => $name,
            'file_path' => $path,
            'file_size' => $file->getSize()
        ];
    }
}
