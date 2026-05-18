<?php

namespace App\Models\Structure;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ushtrimet extends Model
{
    protected $table = 'excs_ushtrimet';
    protected $guarded = [];

    public function kategorite(): BelongsToMany
    {
        return $this->belongsToMany(
            Kategorite::class,
            'excs_kategoria_ushtrimet',
            'ushtrimet_id',
            'kategoria_id'
        );
    }


}
