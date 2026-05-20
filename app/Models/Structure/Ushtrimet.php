<?php

namespace App\Models\Structure;

use App\Models\Human\PjesetETrupit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function njesia (): BelongsTo
    {
        return $this->belongsTo(NjesiaMatese::class, 'id_njesia_matese');
    }

    public function pjeset_e_trupit (): BelongsTo {
        return $this->belongsTo(PjesetETrupit::class, 'id_pjeses_trupit');
    }


}
