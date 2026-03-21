<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'total_amount',
        'status',
    ];

    /**
     * La commande appartient à un utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Une commande contient plusieurs articles (livres).
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Une commande a un seul paiement associé.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
