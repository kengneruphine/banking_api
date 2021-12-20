<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_account_number',
        'destination_account_number',
        'sender_account_type',
        'destination_account_type',
        'amount',
        'charge',
        'status',
        'currency'
    ];

    protected $hidden = [
        'user_id'
    ];


    /**
     * Get the user that owns the transfer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the all accounts affected by a transfer.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

}
