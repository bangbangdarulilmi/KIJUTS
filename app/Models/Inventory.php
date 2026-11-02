<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'name',
        'category',
        'quantity',
        'condition',
        'location',
        'image',
        'notes',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(InventoryLoan::class);
    }
}
