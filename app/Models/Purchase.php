<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['ingredient_id', 'quantity', 'price'])]
class Purchase extends Model
{
    use HasFactory;

    public function ingredient()
    {
        return $this->belongsTo(Inventory::class, 'ingredient_id');
    }
}
