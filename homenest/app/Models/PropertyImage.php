<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{   protected $primaryKey = 'image_id';
    public $timestamps = false;
    public function property() {return $this-> belongsTo(Property::class, 'property_id', 'property_id');}
    public function image(){return $this->hasMany(Property_image::class, 'image_id');}
    public function coverImage()
    {return $this->hasOne(Property_image::class, 'image_id')->where('is_cover', true); }
    public function moderationlogs()
    {return $this->hasMany(Moderations_log::class, 'property_id');}
}
