<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'file_name',
        'file_path',
        'hash',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
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

    public function scopeHashOfLoginUser($query, $hash)
    {
        return $query->loginUser()->where('hash', $hash);
    }

}
