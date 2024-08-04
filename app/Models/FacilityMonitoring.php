<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityMonitoring extends Model
{
    use HasFactory;

    // Specify the table if it's different from the model's pluralized name
    protected $table = 'facility_monitorings';

    // Define which attributes can be mass assigned
    protected $fillable = [
        'facility_id',
        'monitored_by',
        'status',
        'remarks',
        'facility_img',
    ];

    // Define relationships
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function monitoredBy()
    {
        return $this->belongsTo(User::class, 'monitored_by');
    }
}
