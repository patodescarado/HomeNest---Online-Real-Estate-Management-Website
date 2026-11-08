<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    public $timestamps() = false;
    public function properties() {return $this-> belongsTo(Property::class);}

}
