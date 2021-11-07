<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAgency extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_agency_id',
        'second_agency_id',
        'third_agency_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function first_agency()
    {
        return $this->belongsTo(Agency::class, 'first_agency_id', 'id');
    }

    public function second_agency()
    {
        return $this->belongsTo(Agency::class, 'second_agency_id', 'id');
    }

    public function third_agency()
    {
        return $this->belongsTo(Agency::class, 'third_agency_id', 'id');
    }
}
