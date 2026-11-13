<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{   protected $primaryKey = 'image_id';
    protected $fillable = [
        'property_id',
        'image_path',
        'is_cover',
        'sort_order',
    ];
    public $timestamps = false;
    public function property() {return $this-> belongsTo(Property::class, 'property_id', 'property_id');}
    public function image(){return $this->hasMany(Property_image::class, 'image_id');}
    public function coverImage()
    {return $this->hasOne(Property_image::class, 'image_id')->where('is_cover', true); }
    public function moderationlogs()
    {return $this->hasMany(Moderations_log::class, 'property_id');}
}
