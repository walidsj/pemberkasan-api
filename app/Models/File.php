<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function user_file()
    {
        return $this->hasOne(UserFile::class);
    }
}
