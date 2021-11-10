<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'whatsapp',
        'file_id',
        'file',
        'content_type',
        'is_checked',
        'is_notified',
        'is_verified',
        'verificator_id',
        'message'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [
        'is_checked' => 'boolean',
        'is_notified' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verificator()
    {
        return $this->belongsTo(User::class, 'verificator_id', 'id');
    }
}
