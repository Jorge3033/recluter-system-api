<?php

namespace App\Models\SIACH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIACH_AT_Candidate extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'ATT_candidates';
    protected $primaryKey = 'ATT_candidate_id';

    protected $fillable = [
        'candidate_key',
        'name',
        'first_last_name',
        'second_last_name',
        'is_national',
        'street',
        'number_in',
        'number_out',
        'colony',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'curp',
        'ATT_Vacantes_id',
        'status',
        'birth_date',
    ];
    public function setCandidateKeyAttribute($value)
    {
        $this->attributes['candidate_key'] = strtoupper($value);
    }
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
}
