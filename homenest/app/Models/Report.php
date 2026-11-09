<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{   protected $primaryKey = 'report_id';
    public $timestamps = false;
    public function properties() {return $this-> belongsTo(Property::class, 'property_id', 'property_id');}
    public function users() {return $this -> belongsTo (User::class, 'user_id', 'user_id');}
}
