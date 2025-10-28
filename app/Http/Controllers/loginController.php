<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function index(){
        return view('index');
    }
    public function loginAttempt(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|max:50|email',
            'password' => 'required|max:200'
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::User();

            if ($user->perfil === 'almox') {
                return redirect()->route('almox');
            }
            else if ($user->perfil === 'mec') {
                return redirect()->route('mec');
            }
            Auth::logout();
            return redirect('/')->withErrors([
                'email' => 'Sua função de usuário não está configurada corretamente para acesso.'
            ]);
        }
        else{
            return back()->withInput()->withErrors([
            'email' => 'Credenciais inválidas.',
            ])->onlyInput('email');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/'); 
    }

    // User::create([
    // 'email' => $request->email,
    // 'senha' => Hash::make($request->senha),
    // ]);
}
