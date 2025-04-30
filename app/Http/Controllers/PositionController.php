<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $positions = Position::all();
            
            if ($positions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Positions not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'positions' => $positions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve positions: ' . $e->getMessage()
            ], 500);
        }
    }
}
