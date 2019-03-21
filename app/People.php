<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    public function building()
    {
        return $this->belongsTo('App\Building');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    protected $fillable = ['fio', 'position', 'status', 'phone', 'ip_phone', 'building_id', 'group_id', 'room', 'room_type', 'email', 'fax'];
}
