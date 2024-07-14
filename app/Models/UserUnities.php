<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUnities extends Model
{
    use HasFactory;

    public function unity()
    {
        return $this->belongsTo(Unity::class, 'unity_id', 'id');
    }
}
