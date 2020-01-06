<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'text', 'from'
    ];

    protected $casts = [
        'from' => 'boolean'
    ];

    /**
     * @return BelongsTo
     */
    public function subscriber() {
        return $this->belongsTo(Subscriber::class);
    }
}
