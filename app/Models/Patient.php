<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'patient_id', 'name', 'age', 'gender', 'phone', 'email', 'address', 'blood_group'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function bed()
    {
        return $this->hasOne(Bed::class);
    }
}