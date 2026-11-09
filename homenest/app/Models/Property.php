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
    public function type() {return $this -> belongsTo (PropertyType::class, 'type_id');}
    public function image() {return $this -> hasMany (PropertyImage::class, 'image_id');}
    public function district() {return $this-> belongsTo(District::class, 'district_id');}
    public function moderationlogs() {return $this -> hasMany (ModerationsLog::class);}
    public function favorites() {return $this -> belongsToMany (Favorite::class, 'favourites');}
    public function coverImage() { return $this->hasOne(PropertyImage::class)->where('is_cover', true); }

    public function isPublic(): bool
    {
        return $this->status === self::STATUS_APPROVED && $this->expires_at?->isFuture();
    }

    public function scopeApproved($query) 
    {return $query -> where ('status', self::status_approved)
    -> where ('expires_at', '>', now());
    }
}
