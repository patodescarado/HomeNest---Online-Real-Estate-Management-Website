<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModerationLog extends Model
{   protected $primaryKey = 'log_id';
    public $timestamps = false;
    public function properties() {return $this-> belongsTo(Property::class, 'property_id', 'property_id');}
    public function users() {return $this -> belongsTo (User::class);}
}
