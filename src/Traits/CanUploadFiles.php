<?php

namespace Shortcodes\FilesUpload\Traits;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait CanUploadFiles
{

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->fileFields)) {

            if (!$value) {
                return parent::setAttribute($key, null);
            }

            if (($newPath = $this->getNewPath($key, $value)) !== $value) {

                $this->moveToNewPath($key, $value);

                return parent::setAttribute($key, $newPath);
            }
        }

        return parent::setAttribute($key, $value);
    }

    private function getNewPath($key, $value)
    {
        $pathInfo = pathinfo($value);

        $modelName = (new \ReflectionClass($this))->getShortName();

        return config('upload.files_path', 'files') . DIRECTORY_SEPARATOR . Str::snake($modelName) . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . $pathInfo['basename'];
    }

    private function moveToNewPath($key, $value)
    {
        $newPath = $this->getNewPath($key, $value);

        $fileContent = Storage::get($value);

        try {
            Storage::put($newPath, $fileContent);
        } catch (\Exception $e) {
            throw $e;
        }

        return;
    }
}

