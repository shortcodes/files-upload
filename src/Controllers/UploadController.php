<?php

namespace Shortcodes\FilesUpload\Controllers;

use Shortcodes\FilesUpload\Requests\FileUploadRequest;

class UploadController
{

    public function store(FileUploadRequest $request)
    {
        $file = $request->file('file');

        if (!$file->isValid()) {
            return $this->response()->json(['message' => 'Uploaded file is invalid'], 422);
        }

        $path = $file->store(config('upload.path', 'tmp'));

        return response()->json(['path' => $path], 201);
    }
}

