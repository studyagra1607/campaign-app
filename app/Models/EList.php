<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EList extends Model
{
    use HasFactory;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
    
}
