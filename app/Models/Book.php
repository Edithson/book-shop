<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'cover_path',
        'file_path',
        'is_published',
    ];

    /**
     * Un livre peut se retrouver dans plusieurs lignes de commande.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
