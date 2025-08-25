<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APIKEY extends Model
{
    //
    protected $table = "apikeys";
    protected $fillable = [
        'key', 'id' , 'name'
    ];
}
