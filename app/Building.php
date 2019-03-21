<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    public function people()
    {
        return $this->hasMany('App\People');
    }

    public function group()
    {
        return $this->belongsTo('App\BuildGroup', 'group_id');
    }

    protected $fillable = [
        'name', 'address', 'position', 'phone', 'group_id'
    ];
}
