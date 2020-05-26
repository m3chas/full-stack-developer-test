<?php

namespace App\Http\Controllers;

Use Carbon\Carbon;
use App\ParkingLog;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\RequestException;

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
        $checking->minutes = 0;
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

    /**
     * Checkout current vehicle status.
     *
     * @param  Request  $request
     * @return Response
     */
    public function checkout(Request $request)
    {
        // A simple check to avoid a checkout for a car with an closed transaction.
        $checkCarLogStatus = ParkingLog::where('plate_number', $request->plate_number)->where('status', 2)->exists();
        if ($checkCarLogStatus) {
            return response()->json([
                'error' => 1,
                'message' => 'This vehicle already made checkout.',
                'data' => null
            ]);
        }

        // Let's create a new checkout log for this car.
        // Get vehicle type.
        $data['total'] = 0;
        $data['minutes'] = 0;
        $vehicleType = $this->getType($request->plate_number);
        if ($vehicleType['success'] == 1) {

            // Get active transaction for this vehicle.
            $checking = ParkingLog::where('plate_number', $request->plate_number)->where('status', 1)->first();
            $now = Carbon::now();
            $data['minutes'] = $now->diffInMinutes($checking->checking);

            // Rules based on vehicle type.
            switch ($vehicleType['type']) {
                case 1:
                    // Oficial - In this case, nothing to do besides saving the checkout event on the current transaction
                    // since the checking-checkout is already
                    // related to the vehicle with plate_number from parking_logs.
                    $checking->status = 2;
                    $checking->checkout = $now;
                    $checking->minutes = $data['minutes'];
                    $checking->save();
                break;
                case 2:
                    // Resident.

                    // For this example, I will perform an HTTP request for our vehicle service, but on a real system, this should
                    // be sent to queue to be processed on backbround for a faster end-user response.
                    $updateMinutes = $this->updateMinutes($request->plate_number, $data['minutes']);
                    if ($vehicleType['success'] == 1) {
                        // Let's save the checkout event on this transaction.
                        $checking->status = 2;
                        $checking->checkout = $now;
                        $checking->minutes = $data['minutes'];
                        $checking->save();
                    }
                break;
                case 3:
                    // Calculate total based on total of minutes consumed.
                    $data['total'] = $data['minutes'] * 0.05;
                    $checking->status = 2;
                    $checking->checkout = $now;
                    $checking->minutes = $data['minutes'];
                    $checking->save();
                break;
            }
        }

        // All went well, let's return a response.
        return response()->json([
            'error' => 0,
            'message' => 'Car checkout added.',
            'data' => $data
        ]);
    }

    /**
     * Request the vehicle type on vehicle service
     * In a real system this should be added as a laravel package / repository.
     *
     * @param  Request  $request
     * @return Response
     */
    public function getType ($plate_number)
    {
        $data['success'] = 0;
        try {
            // Let's make an HTTP request to our vehicle service.
            $this->http = new Client();
            $response = $this->http->request('POST', env('VEHICLE_SERVICE_HOST') . ':' . env('VEHICLE_SERVICE_PORT') . '/api/type', [
                'json' => [
                    'plate_number' => $plate_number
                ]
            ]);

            // Let's parse the response.
            $response_data = json_decode((string) $response->getBody(), true);
            $data['success'] = 1;
            $data['type'] = $response_data['data']['type'];
            
        } catch (RequestException $e) {
            // In case an error is returned, let's passit to show it on the API response.
            $data['error'] = json_decode((string) $e->getResponse()->getBody(), true);
        }

        return $data;
    }

    /**
     * Request the vehicle type on vehicle service
     * In a real system this should be added as a laravel package / repository.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateMinutes ($plate_number, $minutes)
    {
        $data['success'] = 0;
        try {
            // Let's make an HTTP request to our vehicle service.
            $this->http = new Client();
            $response = $this->http->request('POST', env('VEHICLE_SERVICE_HOST') . ':' . env('VEHICLE_SERVICE_PORT') . '/api/minutes', [
                'json' => [
                    'plate_number' => $plate_number,
                    'minutes' => $minutes
                ]
            ]);

            // Let's parse the response.
            $response_data = json_decode((string) $response->getBody(), true);
            $data['success'] = 1;
            
        } catch (RequestException $e) {
            // In case an error is returned, let's passit to show it on the API response.
            $data['error'] = json_decode((string) $e->getResponse()->getBody(), true);
        }

        return $data;
    }
}