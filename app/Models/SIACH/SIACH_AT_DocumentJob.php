<?php

namespace App\Models\SIACH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIACH_AT_DocumentJob extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'ATT_document_jobs';

    protected $primaryKey = 'ATT_document_jobs_id';


    protected $fillable = [
        'ATT_document_id',
        'ATT_Vacantes_id',
        'is_national',
        'importance',
    ];

}
