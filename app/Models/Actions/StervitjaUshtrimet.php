<?php

namespace App\Models\Actions;

use Illuminate\Database\Eloquent\Model;

class StervitjaUshtrimet extends Model
{
    protected $table = 'act_stervitja_ushtrimet';
    protected $guarded = [];

    public function detaje (){
        return $this->hasMany(StervitjaUshtrimetDetaje::class, 'id_ushtrimit_exct', 'id');
    }

    public function stervitja(){
        return $this->belongsTo(Stervitja::class, 'id_stervitjes');
    }
}
