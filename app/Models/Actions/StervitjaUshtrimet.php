<?php

namespace App\Models\Actions;

use App\Models\Structure\Ushtrimet;
use Illuminate\Database\Eloquent\Model;

class StervitjaUshtrimet extends Model
{
    protected $table = 'act_stervitja_ushtrimet';
    protected $guarded = [];

    public function ushtrimi()
    {
        return $this->belongsTo(Ushtrimet::class, 'id_ushtrimit');
    }

    public function detaje()
    {
        return $this->hasMany(StervitjaUshtrimetDetaje::class, 'id_ushtrimit_exct', 'id');
    }

    public function stervitja()
    {
        return $this->belongsTo(Stervitja::class, 'id_stervitjes');
    }
}
