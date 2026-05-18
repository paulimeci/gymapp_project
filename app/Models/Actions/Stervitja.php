<?php

namespace App\Models\Actions;

use Illuminate\Database\Eloquent\Model;

class Stervitja extends Model
{
    //
    protected $table = 'act_stervitja';
    protected $guarded = [];

    public function ushtrimet (){
        return $this->hasMany(StervitjaUshtrimet::class, 'id_stervitjes', 'id');
    }
}
