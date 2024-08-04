<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentMonitoring extends Model
{
    use HasFactory;

    protected $table = 'equipment_monitorings';

    protected $fillable = [
        'equipment_id',
        'facility_id',
        'monitored_by',
        'status',
        'remarks',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function monitoredBy()
    {
        return $this->belongsTo(User::class, 'monitored_by');
    }
}
