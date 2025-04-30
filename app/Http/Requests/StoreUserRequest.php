<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\TokenController;

class StoreUserRequest extends FormRequest
{
    /**
     * Store token validation error message
     *
     * @var string
     */
    protected $tokenError = 'The token is invalid or has expired.';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Get token from header
        $token = $this->header('Authorization');
        
        // Remove 'Bearer ' prefix if present
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }
        
        // Check if token is provided
        if (!$token) {
            $this->tokenError = 'Token is required.';
            return false;
        }
        
        // Validate token
        list($isValid, $errorMessage) = TokenController::validateToken($token);
        
        if (!$isValid) {
            $this->tokenError = $errorMessage;
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'position_id' => 'required|integer|exists:positions,id',
            'phone' => 'required|string|regex:/\+380[0-9]{9}/|unique:users',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'User with this phone or email already exist',
            'phone.unique' => 'User with this phone or email already exist',
        ];
    }
    
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        
        // Check if the error is for duplicate email or phone
        if ($errors->has('email') && $errors->first('email') === 'User with this phone or email already exist' ||
            $errors->has('phone') && $errors->first('phone') === 'User with this phone or email already exist') {
            
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'User with this phone or email already exist'
                ], JsonResponse::HTTP_CONFLICT) // 409 Conflict
            );
        }
        
        // For other validation errors
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'fails' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
    
    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $this->tokenError
            ], JsonResponse::HTTP_UNAUTHORIZED)
        );
    }
}
