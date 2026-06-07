<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'extinguisher_id', 'inspector_id',
        'action_taken', 'conditions', 'date_of_action'
    ];

    public function extinguisher() {
        return $this->belongsTo(Extinguisher::class);
    }

    public function inspector() {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}