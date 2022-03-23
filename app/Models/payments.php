<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;
    protected $primaryKey="payment_id";
    protected $fillable=[
        'amount_to_be_paid',
        'paid_amount',
        'balance',
        'total_harvest',
        'farmer_id'
     ];
}
