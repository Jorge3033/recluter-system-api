<?php

namespace App\Models\SIACH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIACH_ATT_Vacantes extends Model
{
    use HasFactory;

    //Disable timestamps
    public $timestamps = false;
    protected $table = 'ATT_vacantes';
    protected $primaryKey = 'ID';
    protected $fillable = [
        'Codigo',
        'Titulo',
        'Descripcion',
        'Imagen',
        'FlujoVacante',
        'Usuario',
        'FechaRegister',
        'FechaUpdate',
        'FechaDelete',
        'Estatus'
    ];
}
