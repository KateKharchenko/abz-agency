<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\GetUsersRequest;
use App\Http\Requests\GetUserRequest;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\GetUsersRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetUsersRequest $request)
    {
        try {
            // Get pagination parameters from request
            $count = $request->input('count', 6); // Default 6 users per page
            $page = $request->input('page', 1); // Default to page 1
            
            // Get paginated users
            $users = User::with('position')->paginate($count, ['*'], 'page', $page);
            
            // Check if the requested page exists
            if ($page > $users->lastPage() && $page > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'total_pages' => $users->lastPage(),
                'total_users' => $users->total(),
                'count' => $count,
                'page' => $page,
                'links' => [
                    'next_url' => $users->nextPageUrl(),
                    'prev_url' => $users->previousPageUrl()
                ],
                'users' => $users->items()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $positions = Position::all();
        // return view('users.create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Requests\StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            // Get validated data
            $validatedData = $request->validated();
            
            // Handle image upload
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                
                // Create a unique filename
                $filename = time() . '.jpg';
                $path = public_path('storage/users');
                
                // Make sure the directory exists
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                
                // Save the processed image temporarily
                $tempPath = $path . '/temp_' . $filename;
                
                // Crop and resize the image to 70x70 from the center
                ImageHelper::cropAndResizeImage($image, $tempPath);
                
                // Optimize with TinyPNG API
                $optimizedImage = ImageHelper::optimizeWithTinyPNG($tempPath);
                
                // Save the optimized image
                file_put_contents($path . '/' . $filename, $optimizedImage);
                
                // Remove the temporary file
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                
                // Update the validated data with the image path
                $validatedData['photo'] = $filename;
            }
            
            // Create the user
            $user = User::create($validatedData);
            
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'message' => 'New user successfully registered'
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * 
     * @param \App\Http\Requests\GetUserRequest $request
     * @param int $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(GetUserRequest $request, $user)
    {
        try {
            // Find the user
            $user = User::with('position')->find($user);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            // Format the response
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the user.'
            ], 500);
        }
    }
    
}
