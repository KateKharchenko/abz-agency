<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Token;
use Carbon\Carbon;

class TokenController extends Controller
{
    /**
     * Generate a new token for user registration
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate()
    {
        try {
            // Generate a unique token
            $token = Str::random(40);
            
            // Store the token in the database
            Token::create([
                'token' => $token,
                'expires_at' => Carbon::now()->addMinutes(40),
                'used' => false
            ]);
            
            // Return the token
            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate token: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Validate a token
     * 
     * @param string $token
     * @return array [bool $isValid, string $errorMessage]
     */
    public static function validateToken($token)
    {
        // Check if token exists
        $tokenRecord = Token::where('token', $token)->first();
        
        if (!$tokenRecord) {
            return [false, 'Invalid token.'];
        }
        
        // Check if token is expired
        if ($tokenRecord->expires_at < Carbon::now()) {
            return [false, 'The token expired.'];
        }
        
        // Check if token is already used
        if ($tokenRecord->used) {
            return [false, 'The token has already been used.'];
        }
        
        // Mark the token as used
        $tokenRecord->used = true;
        $tokenRecord->save();
        
        return [true, ''];
    }
}
