<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class farmers extends Model
{
    use HasFactory;
    protected $primaryKey="farmer_id";
    protected $fillable=[
        'farmer_firstname',
        'farmer_lastname',
        'farmer_gender',
        'farmer_phone',
        'site_id',
    ];
}
