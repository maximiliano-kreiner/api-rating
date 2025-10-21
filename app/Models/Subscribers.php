<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
    protected $table = 'pbs_rating.ra_clients';
    public $primaryKey = 'cli_id';
    public $timestamps = false;
    
}
