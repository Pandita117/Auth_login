<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = 'reservaciones';

    protected $fillable = ['user_id', 'equipo_id','estado'];



    //reservacion neceita un usuario que lo reserve 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //la reserva necesita un equipo 
    public function equipo ()
    {
        return $this->belongsTo(Equipo::class);
    }
}
