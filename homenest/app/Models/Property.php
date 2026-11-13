<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PropertyImage;

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
    'bathroom', 'address'];

    protected $primaryKey = 'property_id';

    protected function casts(): array
    {
        return ['expires_at'=> 'datetime']; 
    }

    public function owner() {return $this -> belongsTo (User::class, 'owner_id'); }
    public function type() {return $this -> belongsTo (PropertyType::class, 'type_id');}
    public function image() {return $this -> hasMany (PropertyImage::class, 'property_id','property_id');}
    public function district() {return $this-> belongsTo(District::class, 'district_id');}
    public function moderationlogs() {return $this -> hasMany (ModerationsLog::class);}
    public function favorites() {return $this -> belongsToMany (Favorite::class, 'favourites');}
    public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorites','property_id','user_id')->withTimestamps();
}
    public function coverImage() { return $this->hasOne(PropertyImage::class, 'property_id','property_id')->where('is_cover', true); }

        public function scopeFilter($query, array $f)
    {
        return $query
            ->when($f['type_id'] ?? null,      fn ($q, $v) => $q->where('type_id', $v))
            ->when($f['district_id'] ?? null,  fn ($q, $v) => $q->where('district_id', $v))
            ->when($f['province_id'] ?? null,  fn ($q, $v) => $q->whereHas('district', fn ($d) => $d->where('province_id', $v)))
            ->when($f['price_min'] ?? null,    fn ($q, $v) => $q->where('price', '>=', $v))
            ->when($f['price_max'] ?? null,    fn ($q, $v) => $q->where('price', '<=', $v))
            ->when($f['area_min'] ?? null,     fn ($q, $v) => $q->where('area', '>=', $v))
            ->when($f['area_max'] ?? null,     fn ($q, $v) => $q->where('area', '<=', $v))
            ->when($f['bedrooms'] ?? null,     fn ($q, $v) => $q->where('bedrooms', '>=', $v))
            ->when($f['keyword'] ?? null,      fn ($q, $v) => $q->where(function ($w) use ($v) {
                $w->where('title', 'like', "%{$v}%")
                ->orWhere('address', 'like', "%{$v}%")
                ->orWhere('description', 'like', "%{$v}%");
            }));
    }
    public function isPublic(): bool
    {
        return $this->status === self::status_approved && $this->expires_at?->isFuture();
    }

    public function scopeApproved($query) 
    {return $query -> where ('status', self::status_approved)
    -> where ('expires_at', '>', now());    
    }

/* thêm các thuộc tính ảo (accessors) để hiển thị nhãn cho loại tin rao, giá và diện tích */
        public function getKindLabelAttribute(): string
    {
        return $this->listing_kind === 'rent' ? 'Thuê' : 'Bán';
    }

    public function getPriceLabelAttribute(): string
    {
        $p = (float) $this->price;

        if ($p >= 1_000_000_000) {
            $text = rtrim(rtrim(number_format($p / 1_000_000_000, 2, ',', '.'), '0'), ',') . ' tỷ';
        } elseif ($p >= 1_000_000) {
            $text = rtrim(rtrim(number_format($p / 1_000_000, 1, ',', '.'), '0'), ',') . ' triệu';
        } else {
            $text = number_format($p, 0, ',', '.') . ' đ';
        }

        return $this->listing_kind === 'rent' ? $text . '/tháng' : $text;
    }

    public function getAreaLabelAttribute(): string
    {
        return rtrim(rtrim(number_format((float) $this->area, 1, ',', '.'), '0'), ',') . ' m²';
    }

    public function getCoverUrlAttribute(): string
    {
        $path = $this->coverImage?->image_path;
        return $path ? Storage::url($path) : asset('images/no-image.svg');
}
}
