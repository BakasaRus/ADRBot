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
            $bdate = Carbon::createFromFormat('j.n.Y', $bdate);
            $this->age = Carbon::now()->diffInYears($bdate);
        } else {
            $this->age = 0;
        }
        $this->save();
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
            case 2:
                return 'мужской';
            default:
                return 'не указан';
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
}
