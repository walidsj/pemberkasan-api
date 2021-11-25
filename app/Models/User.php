<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'major_id',
        'agency_id',
        'password',
        'role',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    protected $appends = [
        'file_url'
    ];

    public function getFileUrlAttribute()
    {
        return env('APP_URL') . '/assets/download/user-files/' . $this->id;
    }

    public function user_files()
    {
        return $this->hasMany(UserFile::class);
    }
}
