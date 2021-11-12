<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'is_locked',
        'is_checked',
        'is_notified',
        'is_verified',
        'locked_at',
        'checked_at',
        'notified_at',
        'verified_at',
        'backupped_at',
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
        'is_locked' => 'boolean',
        'is_checked' => 'boolean',
        'is_notified' => 'boolean',
        'is_verified' => 'boolean',
    ];

    protected $appends = [
        'file_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verificator()
    {
        return $this->belongsTo(User::class, 'verificator_id', 'id');
    }

    public function filex()
    {
        return $this->belongsTo(File::class);
    }

    public function getFileUrlAttribute()
    {
        return (!empty($this->file)) ? env('APP_URL') . '/assets/user-uploads/' . explode('_', $this->file)[0] . '/' . $this->file : null;
    }
}
