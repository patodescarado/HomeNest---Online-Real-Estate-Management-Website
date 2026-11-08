<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $timestamps = false;
    public function provinces() {return $this -> belongsTo(Province::class);} 
    public function properties() {return $this -> hasMany(Property::class);}
}
