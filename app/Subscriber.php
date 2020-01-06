<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Subscriber extends Model
{
    protected $fillable = [
        'id', 'name', 'surname', 'bdate', 'sex'
    ];

    /**
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function setInfoFromVk($info)
    {
        $this->name = $info['first_name'];
        $this->surname = $info['last_name'];
        $this->sex = $info['sex'];
        $bdate = $info['bdate'];
        if (isset($bdate) && Carbon::hasFormat($bdate, 'j.n.Y')) {
            \Log::debug($bdate);
            \Log::debug(Carbon::createFromFormat('j.n.Y', $bdate));
            $this->age = Carbon::now()->diffInYears(Carbon::createFromFormat('j.n.Y', $bdate));
        } else {
            \Log::debug($bdate);
            $this->age = 0;
        }
        $this->save();
        return $this;
    }
}
