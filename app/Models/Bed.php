<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $fillable = [
        'bed_number', 'ward', 'bed_type', 'status', 'patient_id'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
