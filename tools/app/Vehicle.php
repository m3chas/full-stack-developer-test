<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Vehicle extends Model
{
    // Collection used for this data model on this test.
    protected $collection = 'vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['plate_number', 'type', 'minutes_current_month'];
    
}
