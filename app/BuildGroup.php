<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuildGroup extends Model
{
    public function buildings()
    {
        return $this->hasMany('App\Building', 'group_id');
    }
    protected $fillable = ['name'];
}
