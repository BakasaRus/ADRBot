<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    public function messages() {
        $this->hasMany(Message::class);
    }
}
