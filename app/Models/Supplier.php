<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'company_name',
        'contact_name',
        'contact_title',
        'contact_number',
        'contact_email',
        'address',
        'city',
        'postcode',
        'state',
        'country',
    ];

    protected $casts = [
        'uuid' => 'string',
        'company_name' => 'string',
        'contact_name' => 'string',
        'contact_title' => 'string',
        'contact_number' => 'string',
        'contact_email' => 'string',
        'address' => 'string',
        'city' => 'string',
        'postcode' => 'string',
        'state' => 'string',
        'country' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // A supplier can provide many products
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_supplier', 'supplier_id', 'product_id');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (isset($filters['company_name'])) {
            $query->where('company_name', 'like', '%' . $filters['company_name'] . '%');
        }

        if (isset($filters['contact_name'])) {
            $query->where('contact_name', 'like', '%' . $filters['contact_name'] . '%');
        }

        if (isset($filters['contact_email'])) {
            $query->where('contact_email', 'like', '%' . $filters['contact_email'] . '%');
        }

        if (isset($filters['country'])) {
            $query->where('country', 'like', '%' . $filters['country'] . '%');
        }

        return $query;
    }
}
