<?php

namespace App\Models\Actions;

use App\Models\Structure\Kategorite;
use Illuminate\Database\Eloquent\Model;

class Stervitja extends Model
{
    //
    protected $table = 'act_stervitja';
    protected $guarded = [];

    public function ushtrimet (){
        return $this->hasMany(StervitjaUshtrimet::class, 'id_stervitjes', 'id');
    }


    public function kategoria()
    {
        return $this->belongsTo(Kategorite::class, 'kategoria_id');
    }
}
