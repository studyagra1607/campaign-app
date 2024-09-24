<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $appends = [
        'emails_count',
        'availables_count',
    ];

    protected $fillable = [
        'name',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emails()
    {
        return $this->belongsToMany(Email::class, 'category_email');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeLoginUser($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function scopeCreateWithLoginUser($query, $data)
    {
        $data['user_id'] = auth()->id();

        return $query->create($data);
    }

    public function getEmailsCountAttribute()
    {
        return $this->emails()->count();
    }

    public function getAvailablesCountAttribute()
    {
        return $this->emails()->where('subscribe', 1)->where('status', 1)->count();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = stringTrim($model->name);
        });
    }
}
