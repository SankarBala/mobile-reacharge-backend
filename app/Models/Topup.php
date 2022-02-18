<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($date) {
        return Carbon::parse($date)->format('d-m-Y h:i A');
    }
    
}
