<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartidaController extends Controller
{
    // Lobby: lista de partidas disponibles, mis partidas y jugadores
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $partidas = Partida::with(['jugadorX', 'jugadorO', 'ganador'])->latest()->get();
            return view('partidas.index', compact('partidas'));
        }

        // esperando al rival y. pues que no seas tu
        $disponibles = Partida::with('jugadorX')
            ->where('estado', 'Esperando')
            ->where('jugador_x_id', '!=', $user->id)
            ->latest()
            ->get();

        // partidas en curso
        $misPartidas = Partida::with(['jugadorX', 'jugadorO', 'ganador'])
            ->where(function ($q) use ($user) {
                $q->where('jugador_x_id', $user->id)
                  ->orWhere('jugador_o_id', $user->id);
            })
            ->latest()
            ->get();

        $jugadores = User::role('jugador')->where('id', '!=', $user->id)->get();

        return view('partidas.index', compact('disponibles', 'misPartidas', 'jugadores'));
    }

    //crear nueva partida el que iniciar la partida es X
    public function store()
    {
        $partida = Partida::create([
            'jugador_x_id' => Auth::id(),
            //iniciamos el tablero vacio
            'tablero'      => ['', '', '', '', '', '', '', '', ''],
            'turno'        => 'X',
            'estado'       => 'Esperando',
        ]);

        return redirect()->route('partidas.show', $partida->id)
            ->with('mensaje', 'Partida creada. Esperando a otro jugador...');
    }

    // unirse  a una partida disponible
    public function unirse($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // en el first or fail es para que no se unan dos al mismo
                $partida = Partida::where('id', $id)->lockForUpdate()->firstOrFail();

                //si ya no esta esperando ya no es disponible por eso es el jugador 0 id null osea ya hay uno 
                if ($partida->estado !== 'Esperando' || $partida->jugador_o_id !== null) {
                    throw new \Exception('Esta partida ya no está disponible.');
                }

                //si eres el mismo chaval que la creo y te tratas de unir no 
                if ($partida->jugador_x_id === Auth::id()) {
                    throw new \Exception('No puedes unirte a tu propia partida.');
                }

                //se actualiza la partidda el estado cuando  ya hay un player O
                $partida->update([
                    'jugador_o_id' => Auth::id(),
                    'estado'       => 'En curso',
                ]);
            });




            return redirect()->route('partidas.show', $id)
                ->with('mensaje', 'Te has unido a la partida como jugador O.');
        } catch (\Exception $e) {
            return redirect()->route('partidas.index')->with('error', $e->getMessage());
        }
    }



    // Ver el tablero de la partida
    public function show(Partida $partida)
    {
        $user = Auth::user();

        // solo los jugadores o el admin pueden ver esta partida
        $esParticipante = $partida->jugador_x_id === $user->id || $partida->jugador_o_id === $user->id;
        if (!$esParticipante && !$user->hasRole('admin')) {
            abort(403, 'No tienes permiso para ver esta partida.');
        }

        return view('partidas.show', compact('partida'));
    }

    // responde en json 
    public function estado(Partida $partida)
    {
        return response()->json([
            'tablero'    => $partida->tablero,
            'turno'      => $partida->turno,
            'estado'     => $partida->estado,
            'resultado'  => $partida->resultado,
            'jugador_o'  => $partida->jugadorO?->name,
        ]);
    }




    // moviemiento 
    public function mover(Request $request, $id)
    {

        $request->validate([
            'casilla' => 'required|integer|min:0|max:8',
        ]);

        
        try {
            DB::transaction(function () use ($request, $id) {
                $partida = Partida::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($partida->estado !== 'En curso') {
                    throw new \Exception('La partida no está en curso.');
                }

                // les da el simbolo dependiendo de que jugaros sea
                $miSimbolo = null;
                if ($partida->jugador_x_id === Auth::id()) {
                    $miSimbolo = 'X';
                } elseif ($partida->jugador_o_id === Auth::id()) {
                    $miSimbolo = 'O';
                } else {
                    throw new \Exception('No perteneces a esta partida.');
                }


                // se valida que sea su tunor. de move 
                if ($partida->turno !== $miSimbolo) {
                    throw new \Exception('No es tu turno.');
                }
                //actualiza casillas y abalero 
                $casilla = (int) $request->casilla;
                $tablero = $partida->tablero;

                // valia que la casilla que pondra este vacia 
                if ($tablero[$casilla] !== '') {
                    throw new \Exception('Esa casilla ya está ocupada.');
                }

                // pone ficha
                $tablero[$casilla] = $miSimbolo;

                // Verifica winner
                $ganadorSimbolo = $this->verificarGanador($tablero);

                //se actualizan. para tener el historial
                if ($ganadorSimbolo) {
                    $partida->update([
                        'tablero'    => $tablero,
                        'estado'     => 'Finalizada',
                        'resultado'  => 'Ganó ' . $ganadorSimbolo,
                        'ganador_id' => $ganadorSimbolo === 'X' ? $partida->jugador_x_id : $partida->jugador_o_id,
                    ]);

                    //arrays que rrecorre el tablero y ve que ya no hay espacio es empate
                } elseif (!in_array('', $tablero, true)) {
                
                    $partida->update([
                        'tablero'   => $tablero,
                        'estado'    => 'Finalizada',
                        'resultado' => 'Empate',
                    ]);
                } else {

                    // si aun hay espacio. y no ganador sigue cambiandno de turno
                    $partida->update([
                        'tablero' => $tablero,
                        'turno'   => $miSimbolo === 'X' ? 'O' : 'X',
                    ]);
                }
            });

            //si  la transaccion con las validaciones estuvo bien sigue si no desvuelve los mensajes de exceptiones
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 422);
        }
    }

    // abandono party da la victoria
    public function abandonar($id)
    {
        DB::transaction(function () use ($id) {
            $partida = Partida::where('id', $id)->lockForUpdate()->firstOrFail();

            if (!in_array($partida->estado, ['Esperando', 'En curso'])) {
                return;
            }

            //cancea 
            if ($partida->estado === 'Esperando' && $partida->jugador_x_id === Auth::id()) {
                $partida->update([
                    'estado'    => 'Cancelada',
                    'resultado' => 'Cancelada por el creador',
                ]);
                return;
            }

                //hace el cmabio de quien es el ganador dependendieo d quien se rindio
            $esX = $partida->jugador_x_id === Auth::id();
            $ganadorId = $esX ? $partida->jugador_o_id : $partida->jugador_x_id;
            $simboloGanador = $esX ? 'O' : 'X';

            $partida->update([
                'estado'     => 'Finalizada',
                'resultado'  => 'Ganó ' . $simboloGanador . ' (por abandono)',
                'ganador_id' => $ganadorId,
            ]);
        });

        return redirect()->route('partidas.index')->with('mensaje', 'Has abandonado la partida.');
    }

    // Cancelar partida del admin
    public function cancelarAdmin(Partida $partida)
    {
        $partida->update([
            'estado'    => 'Cancelada',
            'resultado' => 'Cancelada por el administrador',
        ]);

        return back()->with('mensaje', 'Partida cancelada correctamente.');
    }




    // funcion para ver el ganador
    private function verificarGanador(array $t): ?string
    {
        $lineas = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // Horizontales
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // Verticales
            [0, 4, 8], [2, 4, 6],            // Diagonales
        ];

        //aqui se recorre el arreglo para veri

        foreach ($lineas as [$a, $b, $c]) {
            if ($t[$a] !== '' && $t[$a] === $t[$b] && $t[$a] === $t[$c]) {
                return $t[$a];
            }
        }

        return null;
    }
}