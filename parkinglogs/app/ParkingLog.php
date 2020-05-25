<?php

use Jenssegers\Mongodb\Eloquent\Model;

class ParkingLog extends Model
{
    // Collection used for this data model on this test.
    protected $collection = 'parking_logs';

    // Set collection data to be used as dates on this Lumen service.
    protected $dates = ['checking'];
    protected $dates = ['checkout'];
}