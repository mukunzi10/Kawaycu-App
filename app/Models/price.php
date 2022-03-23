<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class price extends Model
{
    use HasFactory;
    protected $primarykey="price_id";
    
    protected $fillable=[
        'price_amount',
        'site_id'
    ];
}
