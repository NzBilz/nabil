<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'size', 'price'])]
class Menu extends Model
{
    use HasFactory;

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Inventory::class, 'recipes', 'menu_id', 'inventory_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
