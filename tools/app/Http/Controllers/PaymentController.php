<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Get report of residents payments for current month.
     *
     * @return Response
     */
    public function getPayments()
    {
        // For this example I will create a .csv file report.
        // Let's get only resident vehicle types.
        $vehicles = Vehicle::where('type', 2)->orderBy('minutes_current_month', 'desc')->get();

        // Let's build the .csv file and add the total to pay column
        $file = new \Laracsv\Export();
        $file->beforeEach(function ($vehicle) {
            // Now notes field will have this value
            $vehicle->total = $vehicle->minutes_current_month * 0.05; 
        });
        $csvContent = $file->build($vehicles, [
            'plate_number' => 'Num. Placa',
            'minutes_current_month' => 'Tiempo estacionado (min.)',
            'total' => 'Cantidad a pagar'
        ])->getWriter()->getContent();

        // Let's save it to our public directory.
        $fileName = 'payments_report_' . time() . '.csv';
        $fileStorage = Storage::disk('public')->put($fileName, $csvContent);

        // All went well, let's return a response.
        return response()->json([
            'error' => 0,
            'message' => 'Payments report created.',
            'data' => [
                'file' => asset('storage/' . $fileName)
            ]
        ]);
    }

    /**
     * Restart values and start a new month.
     *
     * @return Response
     */
    public function newMonth()
    {
        // Based on the document description:
        // Oficial vehicles will be deleted the parking logs of current month.
        // Since the logs are based on dates and status, it's not necesary.

        // Let's do a massive update againts resident vehicles only.
        $vehicles = Vehicle::where('type', 2)->update(['minutes_current_month' => 0]);

        // All went well, let's return a response.
        return response()->json([
            'error' => 0,
            'message' => 'Values has been reset.',
            'data' => null
        ]);
    }
}
