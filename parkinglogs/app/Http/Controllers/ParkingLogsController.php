<?php

namespace App\Http\Controllers;

Use Carbon\Carbon;
use App\ParkingLog;
use Illuminate\Http\Request;

class ParkingLogsController extends Controller
{
    /**
     * Create a new checking for this car.
     *
     * @param  Request  $request
     * @return Response
     */
    public function checking(Request $request)
    {
        // A simple check to avoid a checking for a car with an open transaction.
        $checkCarLogStatus = ParkingLog::where('plate_number', $request->plate_number)->where('status', 1)->exists();
        if ($checkCarLogStatus) {
            return response()->json([
                'error' => 1,
                'message' => 'This car has an active checking status.',
                'data' => null
            ]);
        }

        // Let's create a new checking log for this car.
        $checking = new ParkingLog;
        $checking->plate_number = $request->plate_number;
        $checking->status = 1;
        $checking->checking = Carbon::now();
        $checking->save();

        // All went well, let's return a response.
        return response()->json([
            'error' => 0,
            'message' => 'Car checking added.',
            'data' => [
                'status' => $checking->status,
                'plate_number' => $checking->plate_number,
                'checking' => $checking->checking
            ]
        ]);
    }
}