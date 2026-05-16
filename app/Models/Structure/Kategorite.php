<?php

namespace App\Models\Structure;

use App\Models\Structure\Ushtrimet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // ← mungonte /

class Kategorite extends Model
{
    //
    protected $table = 'excs_kategorite';
    protected $guarded = [];

    public function ushtrimet(): BelongsToMany
    {
        return $this->belongsToMany(
            Ushtrimet::class,
            'excs_kategoria_ushtrimet',  // tabela pivot
            'kategoria_id',               // FK e këtij modeli
            'ushtrimet_id'                // FK e modelit tjetër
        );
    }

}
