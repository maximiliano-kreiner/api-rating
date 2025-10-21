<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    protected $table = 'pbs_rating.ra_accounts';
    public $primaryKey = 'acc_id';
    public $timestamps = false;
    
}
