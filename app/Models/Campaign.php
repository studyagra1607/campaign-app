<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'category_id',
        'template_id',
        'user_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
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

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = stringTrim($model->name);
        });

        static::saved(function ($model) {
            $model->load('category', 'template');
        });
    }

    protected static function booted()
    {
        static::addGlobalScope(function (Builder $builder) {
            $builder->with('category', 'template');
        });
    }
    
}
