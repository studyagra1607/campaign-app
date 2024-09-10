<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emails()
    {
        return $this->belongsToMany(Email::class, 'category_email');
    }
    
    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeLoginUser()
    {
        return $this->where('user_id', auth()->id());
    }
    
    public function scopeCreateWithLoginUser($query, $data)
    {
        $data['user_id'] = auth()->id();
        return $query->create($data);
    }
    
}
