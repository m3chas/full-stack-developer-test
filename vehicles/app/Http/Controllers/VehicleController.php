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
                'type' => $vehicle->type
            ]
        ]);
    }

    /**
     * Create a new vehicle on the system.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        // A simple check to avoid a checking for a car with an open transaction.
        $checkIfVehicleExist = Vehicle::where('plate_number', $request->plate_number)->exists();
        if ($checkIfVehicleExist) {
            return response()->json([
                'error' => 1,
                'message' => 'This vehicle already exist on record.',
                'data' => null
            ]);
        }

        // Let's create a new vehicle on the system.
        $vehicle = new Vehicle;
        $vehicle->plate_number = $request->plate_number;
        $vehicle->type = $request->type;
        $vehicle->minutes_current_month = 0;
        $vehicle->save();

        // All went well, let's return a response.
        return response()->json([
            'error' => 0,
            'message' => 'Vehicle created.',
            'data' => [
                'plate_number' => $vehicle->plate_number,
                'type' => $vehicle->type
            ]
        ]);
    }

    /**
     * Update vehicle minutes for current month.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateMinutes(Request $request)
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

        // Update minutes_current_month on vehicle.
        $vehicle->minutes_current_month = $vehicle->minutes_current_month + $request->minutes;
        $vehicle->save();

        // All went well, let's return a response.
        return response()->json([
            'error' => 0,
            'message' => 'Car checking added.',
            'data' => [
                'type' => $vehicle->type
            ]
        ]);
    }
}
