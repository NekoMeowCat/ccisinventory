<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EightyNine\Approvals\Models\ApprovableModel;



class Borrow extends ApprovableModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipment_id',
        'facility_id',
        'request_status',
        'request_form',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
