<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('expires_at');
    }

    const TYPES = [
        'vartai' => 'Vartai',
        'lempos' => 'Lempos',
        'kiti' => 'Kiti',
    ];
}
