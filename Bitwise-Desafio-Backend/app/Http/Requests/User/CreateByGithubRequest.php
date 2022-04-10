<?php

namespace App\Http\Requests\User;

use App\Models\Enums\Gender;
use App\Rules\Alphanumeric;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateByGithubRequest extends FormRequest
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
            'userName' => ['string', 'min:5', 'max:30', 'unique:users,userName', 'required', new Alphanumeric],
            'email' => ['required', 'unique:users,email', 'email'],
            'gender' => [new Enum(Gender::class), 'nullable'],
        ];
    }
}
