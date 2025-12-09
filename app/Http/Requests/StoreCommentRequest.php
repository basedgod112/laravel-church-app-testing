<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route middleware 'auth' is applied in routes
    }

    protected function prepareForValidation(): void
    {
        $input = $this->all();

        if (isset($input['body'])) {
            // strip tags to prevent XSS and trim
            $input['body'] = trim(strip_tags($input['body']));
        }

        $this->replace($input);
    }

    public function rules(): array
    {
        return [
            'body' => 'required|string|max:1000',
        ];
    }
}

