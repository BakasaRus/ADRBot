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

    protected $appends = ['last_message_at'];

    /**
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function setInfoFromVk($info)
    {
        $this->attributes['name'] = $info['first_name'];
        $this->attributes['surname'] = $info['last_name'];
        $this->attributes['sex'] = $info['sex'];
        $bdate = array_key_exists('bdate', $info) ? $info['bdate'] : '';
        if (isset($bdate) && Carbon::hasFormat($bdate, 'j.n.Y')) {
            $bdate = Carbon::createFromFormat('j.n.Y', $bdate);
            $this->attributes['age'] = Carbon::now()->diffInYears($bdate);
        } else {
            $this->attributes['age'] = 0;
        }
        return $this;
    }

    public function getFullNameAttribute()
    {
        return "{$this->attributes['name']} {$this->attributes['surname']}";
    }

    public function getReadableSexAttribute()
    {
        switch ($this->attributes['sex']) {
            case 1:
                return 'женский';
                break;
            case 2:
                return 'мужской';
                break;
            default:
                return 'не указан';
                break;
        }
    }

    public function setReadableSexAttribute($value)
    {
        if ($value == 'мужской' || $value == 'муж' || $value == 'мужчина') {
            $this->attributes['sex'] = 2;
        } else if ($value == 'женский' || $value == 'жен' || $value == 'женщина') {
            $this->attributes['sex'] = 1;
        } else {
            $this->attributes['sex'] = 0;
        }
    }

    public function getLastMessageAtAttribute()
    {
        return $this->messages->last()->created_at;
    }
}
