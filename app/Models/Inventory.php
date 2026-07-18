<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'stock', 'unit', 'min_stock'])]
class Inventory extends Model
{
    use HasFactory;

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'ingredient_id');
    }
}
