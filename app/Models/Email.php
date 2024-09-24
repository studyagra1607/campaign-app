<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $appends = [
        'category_ids',
    ];

    protected $fillable = [
        'name',
        'email',
        'subscribe',
        'status',
        'user_id',
    ];

    protected $casts = [
        'subscribe' => 'boolean',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_email');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeSubscribe($query)
    {
        return $query->where('subscribe', 1);
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

    public function getCategoryIdsAttribute()
    {
        return $this->categories->pluck('id')->toArray();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = stringTrim($model->name);
            $model->email = stringTrim(strtolower($model->email));
        });

        static::saved(function ($model) {
            $model->categories()->sync(request()->input('category_ids'));
            $model->load('categories');
        });
    }
}
