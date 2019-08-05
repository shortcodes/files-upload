<?php

namespace Shortcodes\FilesUpload\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Shortcodes\FilesUpload\Requests\FileUploadRequest;

class UploadController
{

    public function store(FileUploadRequest $request)
    {
        $file = $request->file('file');

        if (!$file->isValid()) {
            return $this->response()->json(['message' => 'Uploaded file is invalid'], 422);
        }

        $path = $file->store(config('upload.tmp_path', 'tmp'));

        return response()->json([
            'path' => App::make('url')->to('/v1') . '/files/'.$path
        ], 201);
    }

    public function show($url)
    {
        if (!Storage::exists($url)) {
            abort(404);
        }

        $width = request()->get('width');
        $height = request()->get('height');

        $mimeType = Storage::mimeType($url);

        if (!$width && !$height) {
            return response(Storage::get($url), 200)->header('Content-Type', $mimeType);
        }

        $pathInfo = pathinfo($url);
        $thumbPath = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '_' . ($width ? 'w' . $width : '') . '_' . ($height ? 'h' . $height : '') . '.' . $pathInfo['extension'];

        if (Storage::exists($thumbPath)) {
            return response(Storage::get($thumbPath), 200)->header('Content-Type', $mimeType);
        }

        $file = Storage::get($url);
        $image = Image::make($file);

        return $this->generateThumbnail($image, $width, $height, $thumbPath, $mimeType);
    }

    private function generateThumbnail($image, $width, $height, $thumbPath, $mimeType)
    {
        if ($image->width() < $image->height()) {
            $tempWidth = $width;
            $tempHeight = $height;

            $width = $tempHeight;
            $height = $tempWidth;
        }

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        Storage::put($thumbPath, (string) $image->stream());

        $response = response($image->stream(), 200);
        $response->header('Content-Type', $mimeType);
        $response->header("pragma", "private");
        $response->header("Cache-Control", "private, max-age=86400");
        $response->header("ETag", md5($thumbPath));

        return $response;
    }
}

