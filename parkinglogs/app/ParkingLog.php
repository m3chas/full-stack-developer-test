<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class ParkingLog extends Model
{
    // Collection used for this data model on this test.
    protected $collection = 'parking_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['checking', 'checkout', 'plate_number', 'status', 'minutes', 'total'];

    // Set collection data to be used as dates on this Lumen service.
    protected $dates = ['checking', 'checkout'];
}