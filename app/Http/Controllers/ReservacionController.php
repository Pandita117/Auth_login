<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Reservacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservacionController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $reservaciones = Reservacion::with(['user', 'equipo'])->latest()->get();
        } else {
            $reservaciones = Reservacion::with('equipo')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('reservaciones.index', compact('reservaciones'));
    }

    public function store(Equipo $equipo)
    {
        $reservado = DB::transaction(function () use ($equipo) {
            $equipoBloqueado = Equipo::where('id', $equipo->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($equipoBloqueado->estado !== 'Disponible') {
                return false;
            }

            Reservacion::create([
                'user_id'   => Auth::id(),
                'equipo_id' => $equipoBloqueado->id,
                'estado'    => 'Activa',
            ]);

            $equipoBloqueado->update(['estado' => 'Ocupado']);

            return true;
        });

        if (!$reservado) {
            return back()->with('error', 'Este equipo ya fue reservado por otro usuario.');
        }

        return redirect()->route('reservaciones.index')
            ->with('mensaje', 'Equipo reservado correctamente.');
    }

    public function cancelar(Reservacion $reservacion)
    {
        if (!Auth::user()->hasRole('admin') && $reservacion->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar una reservación que no te pertenece.');
        }

        if ($reservacion->estado !== 'Activa') {
            return back()->with('error', 'Solo se pueden cancelar reservaciones activas.');
        }

        DB::transaction(function () use ($reservacion) {
            $reservacion->update(['estado' => 'Cancelada']);
            $reservacion->equipo->update(['estado' => 'Disponible']);
        });

        return back()->with('mensaje', 'Reservación cancelada y equipo liberado.');
    }
}