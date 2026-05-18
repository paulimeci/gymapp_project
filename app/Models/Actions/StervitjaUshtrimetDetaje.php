<?php

namespace App\Models\Actions;

use Illuminate\Database\Eloquent\Model;

class StervitjaUshtrimetDetaje extends Model
{
    protected $table = 'act_stervitja_ushtrimet_detaje';
    protected $guarded = [];
    public function stervitja(){
        return $this->belongsTo(StervitjaUshtrimet::class, 'id_ushtrimit_exct');
    }

}
