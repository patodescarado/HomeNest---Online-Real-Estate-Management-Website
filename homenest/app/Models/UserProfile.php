<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $fillable = ['user_id', 'full_name', 'phone', 'avatar', 'bio'];
    public function user() { return $this->belongsTo(User::class); }
}
