<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Get vehicle type.
     *
     * @param  Request  $request
     * @return Response
     */
    public function getType(Request $request)
    {
        // Search vehicle based on plate number, if not found, return a not found response.
        $vehicle = Vehicle::where('plate_number', $request->plate_number)->first();
        if (!$vehicle) {
            return response()->json([
                'error' => 1,
                'message' => 'Vehicle not found.',
                'data' => null
            ]);
        }

        // All went well, let's return a response.
        return response()->json([
            'error' => 0,
            'message' => 'Car checking added.',
            'data' => [
                'type' => $vehicle->type,
                'minutes_current_month' => $minutes_current_month,
            ]
        ]);
    }
}
