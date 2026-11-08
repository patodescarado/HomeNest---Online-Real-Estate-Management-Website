<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PropertyType extends Model
{
    public $timestamps = false;
    public function properties() {return $this -> belongsTo(Property::class);}
    public function scopeForSale($query)
    {
        return $query->where('listing_kind', 'sale');
    }

    public function scopeForRent($query)
    {
        return $query->where('listing_kind', 'rent');
    }

}
