<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AnalysisController extends Controller
{
    /**
     * Show the analysis page with available crops
     */
    public function index() 
    {
        try {
            $crops = Cache::remember('available_crops', 3600, function () {
                $response = Http::timeout(10)->get('http://127.0.0.1:8001/crops'); // <-- LOCALHOST
                if ($response->successful()) {
                    return $response->json()['crops'] ?? [];
                }
                return [];
            });
        } catch (\Exception $e) {
            Log::error('Failed to fetch crops', ['error' => $e->getMessage()]);
            // fallback crops
            $crops = ['rambutan', 'potato', 'beetroot', 'carrot', 'pumpkin', 'mango', 'banana'];
        }
        
        return view('analysis', compact('crops'));
    }

    /**
     * Send prediction request to ML service
     */
    public function predict(Request $request) 
    {
        $validated = $request->validate([
            'crop' => 'required|string',
            'days' => 'required|integer|in:7,15,30,60',
        ]);

        try {
            Log::info('Starting prediction request', $validated);
            
            // Send request to FastAPI ML service (localhost)
            $response = Http::timeout(90)->post('http://127.0.0.1:8001/predict', [
                'crop' => $validated['crop'],
                'days' => (int) $validated['days'],
            ]);

            Log::info('Prediction response received', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['success']) && $result['success'] === false) {
                    return response()->json([
                        'success' => false,
                        'error' => $result['error'] ?? 'Unknown error'
                    ], 500);
                }
                
                return response()->json($result);
            } else {
                Log::error('FastAPI error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Prediction service error',
                    'message' => $response->body()
                ], 500);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Cannot connect to prediction service',
                'message' => 'Make sure FastAPI ML service is running on port 8001'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Prediction failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Prediction request failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
