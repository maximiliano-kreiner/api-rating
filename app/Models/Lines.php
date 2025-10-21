<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lines extends Model
{
    protected $table = 'pbs_rating.ra_tariff_identification';
    public $timestamps = false;

    protected $primaryKey = null;
    public $incrementing = false;
    public function getKeyName()
    {
        return null;
    }
}
