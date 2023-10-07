<?php

namespace App\Http\Requests;

use App\Http\Resources\Error\NoRight;
use App\Http\Resources\Error\RequestNotValidated;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReportRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:200',
            'description' => 'required|max:2000',
            'location_api' => 'required|max:200',
            'location_text' => 'required|max:200',
            'photo.*' => 'required|image|max:51200',
        ];
    }
}
