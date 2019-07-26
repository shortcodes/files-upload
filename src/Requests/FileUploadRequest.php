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
        $rules = [
            'file' => [
                'required',
                'file',
                'max:' . config('upload.max_file_size'),
            ],
        ];

        if ($this->getAllowedMimetypes()) {
            $rules['file'][] = 'mimetypes:' . $this->getAllowedMimetypes();
        }

        return $rules;
    }

    private function getAllowedMimetypes()
    {
        return implode(',', config('upload.allowed_mimetypes'));
    }
}
