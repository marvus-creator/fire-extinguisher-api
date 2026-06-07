<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extinguisher extends Model
{
    protected $fillable = [
        'serial_number', 'location', 'type', 'size',
        'installation_date', 'expiry_date', 'status'
    ];
}