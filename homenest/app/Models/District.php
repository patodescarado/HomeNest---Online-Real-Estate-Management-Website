<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{   protected $primaryKey = 'district_id';
    public $timestamps = false;
    public function province() {return $this -> belongsTo(Province::class,'province_id', 'province_id');} 
    public function properties() {return $this -> hasMany(Property::class, 'district_id', 'district_id');}
}
