<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'description' => 'string',
        'image' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // A category has many products
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'uuid');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        return $query;
    }
}
