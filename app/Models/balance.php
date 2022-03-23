<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class balance extends Model
{
    use HasFactory;
    protected $primaryKey="balance_id";
    protected $fillable=[
        'bTotal_harvest',
        'bTotal_amount',
        'farmer_id',
        'site_id'
    ];
}
