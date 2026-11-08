<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    const status_draft = 'draft';
    const status_pending = 'pending';
    const status_approved = 'approved';
    const status_rejected = 'rejected';
    const status_expired = 'expired';
    const status_hidden = 'hidden';

    protected $fillable = 
    ['owner_id', 'type_id', 'district_id',  'title', 'description', 
    'listing_kind', 'price', 'area', 'bedrooms',
    'bathroom', 'address', 'latitude', 'longitude'];

    protected $primaryKey = 'property_id';

    protected function casts(): array
    {
        return ['expires_at'=> 'datetime']; 
    }

    public function owner() {return $this -> belongsTo (User::class, 'owner_id'); }
    public function types() {return $this -> belongsTo (Property_type::class, 'type_id');}
    public function images() {return $this -> hasMany (Property_image::class);}
    public function districts() {return $this-> belongsTo(District::class);}
    public function moderationlogs() {return $this -> hasMany (Moderations_log::class);}
    public function favorites() {return $this -> belongsToMany (Favorite::class, 'favourites');}

    public function approved($query) 
    {return $query -> where ('status', self::status_approved)
    -> where ('expires_at', '>', now());
    }
}
