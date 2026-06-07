<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'extinguisher_id', 'inspector_id',
        'scheduled_date', 'status', 'notes'
    ];

    public function extinguisher() {
        return $this->belongsTo(Extinguisher::class);
    }

    public function inspector() {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}