<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'book_id',
        'price',
    ];

    /**
     * Cet article appartient à une commande globale.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Cet article correspond à un livre spécifique.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
