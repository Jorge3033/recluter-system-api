<?php

namespace App\Models\SIACH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIACH_AT_Document extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'ATT_documents';
    protected $primaryKey = 'ATT_document_id';

    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'file_system_location',
        'has_ocr',
        'ocr_is_active',
        'ocr_is_active_justification',
        'ocr_strategy',
    ];


    //Setter to OCRStrategy
    public function setOCRStrategyAttribute($value)
    {
        // replace spaces with underscores
        $name = str_replace(' ', '_', $this->name);
        $name = strtolower($name);
        $this->attributes['ocr_strategy'] = 'ocr_'.$name;
    }


}
