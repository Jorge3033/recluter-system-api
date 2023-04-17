<?php

namespace App\Models\SIACH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIACH_AT_CandidateDocuments extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ATT_candidate_documents';
    protected $primaryKey = 'ATT_candidate_document_id';

    protected $fillable = [
        'name',
        'path',
        'file_system_location',
        'ATT_candidate_id',
        'ATT_document_id',
        'document_type',
        'document_is_national',
        'document_importance',
        'ocr_strategy',
        'ocr_api_id',
        'status',
    ];
}
