<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        switch ($this->method()) {
            case 'PUT':
                return [
                    "name" => "required",
                    "versionCode" => "required|numeric",
                    "apiId" => "required",
                ];

            default:
                return [];
        }
    }
}
