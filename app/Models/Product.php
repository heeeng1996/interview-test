<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'quantity',
        'image',
        'discount',
        'rating',
        'review',
    ];

    protected $casts = [
        'uuid' => 'string',
        'category_id' => 'string',
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'image' => 'string',
        'discount' => 'decimal:2',
        'rating' => 'decimal:2',
        'review' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
    
    // A product belongs to a single category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'uuid');
    }

    // A product can belong to many suppliers
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier', 'product_id', 'supplier_id');
    }

    protected function finalPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->discount > 0 ? round($this->price - ($this->price * ($this->discount / 100)), 2) : $this->price
        );
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['category_id'] ?? null, function ($q, $categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($filters['min_price'] ?? null, function ($q, $minPrice) {
                $q->where('price', '>=', $minPrice);
            })
            ->when($filters['max_price'] ?? null, function ($q, $maxPrice) {
                $q->where('price', '<=', $maxPrice);
            })
            ->when($filters['min_rating'] ?? null, function ($q, $minRating) {
                $q->where('rating', '>=', $minRating);
            })
            ->when($filters['max_rating'] ?? null, function ($q, $maxRating) {
                $q->where('rating', '<=', $maxRating);
            })
            ->when($filters['stock_level'] ?? null, function ($q, $stockLevel) {
                if ($stockLevel === 'in_stock') {
                    $q->where('quantity', '>', 5);
                } elseif ($stockLevel === 'out_of_stock') {
                    $q->where('quantity', '=', 0);
                } elseif ($stockLevel === 'low_stock') {
                    $q->where('quantity', '<=', 5)->where('quantity', '>', 0);
                }
            });
    }
}
