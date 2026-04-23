<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, \App\Core\Tenancy\Traits\HasTenant;

    protected $fillable = ['tenant_id', 'category_id', 'name', 'price', 'stock'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
