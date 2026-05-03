<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id','name','code','description','unit','quantity_in_stock',
        'minimum_stock_level','quantity_issued','unit_cost','supplier',
        'grade_level','is_textbook','is_active',
    ];

    protected $casts = ['is_textbook' => 'boolean', 'is_active' => 'boolean', 'unit_cost' => 'decimal:2'];

    public function category()     { return $this->belongsTo(InventoryCategory::class); }
    public function transactions() { return $this->hasMany(InventoryTransaction::class, 'item_id'); }

    public function isLowStock(): bool { return $this->quantity_in_stock <= $this->minimum_stock_level; }
    public function isOutOfStock(): bool { return $this->quantity_in_stock === 0; }

    public function scopeActive($q)    { return $q->where('is_active', true); }
    public function scopeLowStock($q)  { return $q->whereColumn('quantity_in_stock', '<=', 'minimum_stock_level'); }
    public function scopeTextbooks($q) { return $q->where('is_textbook', true); }
}
