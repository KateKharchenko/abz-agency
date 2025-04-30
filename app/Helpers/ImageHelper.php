<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageHelper
{
    /**
     * Optimize an image using TinyPNG API
     *
     * @param string $imagePath Path to the image file
     * @return string Optimized image content
     * @throws \Exception If optimization fails
     */
    public static function optimizeWithTinyPNG($imagePath)
    {
        try {
            // TinyPNG API key - you should store this in your .env file
            $apiKey = env('TINYPNG_API_KEY');
            
            if (!$apiKey) {
                throw new \Exception('TinyPNG API key is not set');
            }
            
            // Read the image file
            $imageData = file_get_contents($imagePath);
            
            // Send the image to TinyPNG API for compression
            $response = Http::withBasicAuth('api', $apiKey)
                ->withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ])
                ->withBody($imageData, 'image/jpeg')
                ->post('https://api.tinify.com/shrink');
            
            // Check if the request was successful
            if ($response->status() !== 201) {
                $errorMessage = $response->json('message') ?? 'Unknown error';
                throw new \Exception('TinyPNG API error: ' . $errorMessage);
            }
            
            // Get the URL of the optimized image
            $resultUrl = $response->json('output.url');
            
            // Download the optimized image
            $optimizedImageResponse = Http::get($resultUrl);
            
            if (!$optimizedImageResponse->successful()) {
                throw new \Exception('Failed to download optimized image');
            }
            
            return $optimizedImageResponse->body();
            
        } catch (RequestException $e) {
            throw new \Exception('TinyPNG API request failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Crop and resize an image to 70x70 pixels from the center
     *
     * @param mixed $image The image resource or path
     * @param string $outputPath The path to save the processed image
     * @return bool True if successful
     * @throws \Exception If processing fails
     */
    public static function cropAndResizeImage($image, $outputPath)
    {
        try {
            // Create a new ImageManager instance with GD driver
            $manager = new ImageManager(new Driver());
            
            // Process image with Intervention Image
            $img = $manager->read($image);
            
            // Get image dimensions
            $width = $img->width();
            $height = $img->height();
            $size = min($width, $height);
            
            // Calculate crop position (center)
            $x = ($width - $size) / 2;
            $y = ($height - $size) / 2;
            
            // Crop the image to a square from the center, resize to 70x70, and save
            $img->crop($size, $size, $x, $y)
                ->resize(70, 70)
                ->encodeByExtension('jpg', 90)
                ->save($outputPath);
                
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Image processing failed: ' . $e->getMessage());
        }
    }
}
