<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * Trim and strip tags to reduce XSS payloads stored in DB.
     */
    protected function prepareForValidation(): void
    {
        $input = $this->all();

        if (isset($input['name'])) {
            $input['name'] = trim(strip_tags($input['name']));
        }

        if (isset($input['email'])) {
            $input['email'] = trim($input['email']);
        }

        if (isset($input['message'])) {
            // allow basic line breaks but strip HTML tags
            $input['message'] = trim(strip_tags($input['message']));
        }

        $this->replace($input);
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'name' => 'name',
            'email' => 'email',
            'message' => 'message',
        ];
    }
}

