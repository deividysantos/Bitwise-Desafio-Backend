<?php

namespace App\Http\Requests\User;

use App\Models\Enums\Gender;
use App\Rules\Alphanumeric;
use App\Rules\JustLetters;
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
    public function rules(): array
    {
        return [
            'userName' => ['string', 'min:5', 'max:30', 'unique:users,userName', 'nullable', new Alphanumeric],
            'name' => ['required', 'min:3', 'max:30', new JustLetters],
            'lastName' => ['min:3', 'max:30', 'nullable', new JustLetters],
            'profileImageUrl' => ['nullable', 'url'],
            'bio' => ['nullable', 'min:3', 'max:30', new JustLetters],
            'email' => ['required', 'unique:users,email', 'email'],
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
