<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function subscriber() {
        return $this->belongsTo(Subscriber::class);
    }
}
