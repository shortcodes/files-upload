<?php

namespace Shortcodes\FilesUpload\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => [
                'required',
                'mimetypes:' . $this->getAllowedMimetypes(),
                'file',
                'max:' . config('upload.max_file_size'),
            ],
        ];
    }

    private function getAllowedMimetypes()
    {
        return implode(',', config('upload.allowed_mimetypes'));
    }
}
