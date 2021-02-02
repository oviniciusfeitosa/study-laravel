<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialIdentity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'provider_name', 'provider_id'];

    public function user()
    {
        return $this->belongsTo(App\User::class);
    }
}
