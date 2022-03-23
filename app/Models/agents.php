<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class agents extends Model
{
    use HasFactory;

    protected $primaryKey="agent_id";
    protected $fillable=[
        'agent_names',
        'agent_phone',
        'agent_gender',
        'site_id',
        'sector',
        'cell',
        'village',
        'agent_status'
    ];

}
