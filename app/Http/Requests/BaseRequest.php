<?php

namespace App\Http\Requests;

use App\Http\Resources\Error\NoRight;
use App\Http\Resources\Error\RequestNotValidated;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(new RequestNotValidated($validator), 400));
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json(new NoRight(null), 400));
    }
}
