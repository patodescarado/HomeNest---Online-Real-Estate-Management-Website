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
    'bathroom', 'address', 'latitude', 'longtitude'];

    protected $function = casts(): array
    {
        return ['expired_at'=> 'datetime']; 
    }

    public function owners() {return $this -> belongsto (users::class, 'owner_id'); }
    public function types() {return $this -> belongsto (property_types::class, 'type_id');}
    public function images() {return $this -> hasmany (property_images::class);}
    public function districts() {return $this-> belongsto(districts::class);}
    public function moderationlogs() {return $this -> hasmany (moderations_logs::class);}
    public function favorites() {return $this -> belongsto_many (favorites::class, 'favourites')}

    public function approved($query) 
    {return $query -> where ('status', self::status_approved)
    -> where ('expired_at', '>', now())
    }
}
