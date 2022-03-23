<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class site extends Model
{
    use HasFactory;

    protected $primaryKey="site_id";
    protected $fillable=[
        'site_name',            
        'sector',
        'cell',
        'village',
        'site_status'
    ];
}
