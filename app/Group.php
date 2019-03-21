<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function people()
    {
        return $this->hasMany('App\People');
    }

    protected
        $fillable = ['name', 'parent_id', 'priority', 'email', 'phone'];

}
