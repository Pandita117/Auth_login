<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Notifications\SendTwoFactorCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            $code = rand(100000, 999999);
            $user->timestamps = false;
            $user->forceFill([
                'two_factor_code' => $code,
                'two_factor_expires_at' => now()->addMinutes(5),
            ])->save();

            // Generar la URL temporal firmada gigante que expira en 5 minutos
            $urlTemporal = URL::temporarySignedRoute(
                'admin.autenti',
                now()->addMinutes(5),
                ['token' => Str::random(64)]
            );

            try {
                $user->notifyNow(new SendTwoFactorCode($code));
            } catch (\Throwable $e) {
                Log::error('Error enviando código 2FA: ' . $e->getMessage());
                return redirect()->to($urlTemporal)->with('error', 'No se pudo enviar el correo: ' . $e->getMessage());
            }

            return redirect()->to($urlTemporal);
        }

        // 2. Si es Empleado, continúa su flujo normal a /categoria
        return redirect()->intended($this->redirectPath());
    }



}
