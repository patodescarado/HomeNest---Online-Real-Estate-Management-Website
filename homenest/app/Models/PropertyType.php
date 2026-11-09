<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PropertyType extends Model
{   protected $primaryKey = 'type_id';
    public $timestamps = false;
    public function properties() {return $this -> belongsTo(Property::class, 'type_id', 'type_id');}
    public function scopeForSale($query)
    {
        return $query->where('listing_kind', 'sale');
    }

    public function scopeForRent($query)
    {
        return $query->where('listing_kind', 'rent');
    }

}
