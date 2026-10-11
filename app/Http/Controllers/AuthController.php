<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Login, registro, perfil y recuperación de contraseña (apps login/ y signin/ de Django).
 * Los mensajes se envían como session('messages') = [['tags' => ..., 'text' => ...]],
 * equivalente al framework de mensajes de Django que usan las plantillas.
 */
class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $costumer = Costumer::where('email', $request->input('email'))->first();

        if (! $costumer) {
            return $this->back('error', 'Correo no encontrado');
        }

        // Los hashes pbkdf2 de Django no son compatibles: esos usuarios deben restablecer su contraseña
        if (! Hash::isHashed($costumer->password) || ! Hash::check($request->input('password'), $costumer->password)) {
            return $this->back('error', 'Contraseña incorrecta');
        }

        Auth::login($costumer);
        $request->session()->regenerate();
        $costumer->forceFill(['last_login' => now()])->save();

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showSignin(): View
    {
        return view('signin');
    }

    public function signin(Request $request): RedirectResponse
    {
        $data = $request->only(['name', 'lastName', 'email', 'phone', 'curp', 'rfc', 'pwd', 'confirm_pwd']);

        $duplicado = Costumer::where('email', $data['email'] ?? null)
            ->orWhere('phone', $data['phone'] ?? null)
            ->orWhere('CURP', $data['curp'] ?? null)
            ->orWhere('rfc', $data['rfc'] ?? null)
            ->exists();

        if ($duplicado) {
            return $this->back('error', 'Datos ya registrados, por favor revisar!');
        }

        if (($data['pwd'] ?? null) !== ($data['confirm_pwd'] ?? null)) {
            return $this->back('error', 'Las contraseñas no coinciden');
        }

        Costumer::create([
            'first_name' => $data['name'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'CURP' => $data['curp'],
            'rfc' => $data['rfc'],
            'password' => $data['pwd'],
        ]);

        return redirect()->route('login')->with('messages', [
            ['tags' => 'success', 'text' => '¡Cuenta creada, ya puedes iniciar sesión!'],
        ]);
    }

    public function perfil(): View
    {
        return view('perfilUsuario', ['user' => Auth::user()]);
    }

    public function actualizarPerfil(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($request->input('tipo_formulario') === 'personal') {
            $user->update([
                'first_name' => trim($request->input('first_name', '')),
                'last_name' => trim($request->input('last_name', '')),
                'phone' => trim($request->input('phone', '')),
            ]);
            $mensaje = 'Información personal actualizada.';
        } elseif ($request->input('tipo_formulario') === 'fiscal') {
            $user->update([
                'rfc' => trim($request->input('rfc', '')),
                'CURP' => trim($request->input('curp', '')),
            ]);
            $mensaje = 'Datos fiscales actualizados.';
        }

        return redirect()->route('login.perfil')->with('messages', isset($mensaje)
            ? [['tags' => 'success', 'text' => $mensaje]]
            : []);
    }

    public function showPasswordForgot(): View
    {
        return view('forgotPwd');
    }

    public function passwordForgot(Request $request): RedirectResponse
    {
        // TODO: portar password_forgot (genera OTP, lo guarda en tempPassword y envía emails.codePwd)
        return $this->back('info', 'Recuperación de contraseña pendiente de implementar');
    }

    public function showPasswordRecovery(): View
    {
        return view('recoveryPwd');
    }

    public function passwordRecovery(Request $request): RedirectResponse
    {
        // TODO: portar password_recovery (valida el OTP firmado y cambia la contraseña)
        return $this->back('info', 'Recuperación de contraseña pendiente de implementar');
    }

    private function back(string $tag, string $text): RedirectResponse
    {
        return back()->withInput()->with('messages', [['tags' => $tag, 'text' => $text]]);
    }
}
