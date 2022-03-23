<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class harvest extends Model
{
    use HasFactory;

    protected $primaryKey="harvest_id";
    protected $fillable=[
        'harvest_quantity',
        'harvest_unitPrice',
        'harvest_totalPrice',
        'farmer_id',
        'site_id'
    ];
}
