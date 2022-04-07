<?php

namespace App\Http\Requests\User;

use App\Models\Enums\Gender;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class CreateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'userName' => ['string', 'min:5', 'max:30', 'unique:users', 'nullable'],
            'name' => ['required', 'min:3', 'max:30'],
            'lastName' => ['min:3', 'max:30', 'nullable'],
            'profileImageUrl' => ['nullable', 'url'],
            'bio' => ['nullable', 'min:3', 'max:30'],
            'email' => ['required', 'unique:users', 'email'],
            'gender' => [new Enum(Gender::class), 'nullable']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
        ], 400);

        throw new HttpResponseException($response);
    }
}
