<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $fillable = [
        'user_id',
        'calificacion',
        'faltas' ,
    ]   ;

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function getEquivalenciaAttribute():string
    {

    $cal = (float) $this->calificacion;


    //seguna lA CALI 
    if($cal >= 90) {
        return 'AU';
    } elseif ($cal >= 80) {
        return 'DE';
    } elseif ($cal >=70) {
        return 'SA';
    }

    return 'NA';
    }
    
}
