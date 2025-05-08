<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    //
    public function login() {
        return view('auth.login');
    }

    public function  authenticate(Request $request): RedirectResponse
    {
        //  form validacao
        $credentials = $request->validate(
            [
                'username' => 'required|min:4|max:30',
                'password' => 'required|min:8|max:30|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
            [
                'username.required' => 'campo :attribute es obrigatorio',
                'username.min' => 'Nome no campo :attribute precisa ter pelo menos :min caracteres ',
                'username.max' => 'Nome no campo :attribute precisa ter no maximo :max caracteres ',
                'password.required' => 'campo :attribute es obrigatorio',
                'password.min' => ' O :attribute precisa ter pelo menos :min numero ',
                'password.max' => 'O :attribute precisa ter no maximo :max numero ',
                'password.regex' => 'A :attribute deve conter pelo menos uma letra maiúscula, uma letra minúscula, e um numero ',
            ]
        );

        // login tradicional do laravel | só user tem email e password
//        if (Auth::attempt($credentials)) {
//            $request->session()->regenerate();
//            return redirect()->route('home');
//        }

        // verificar se o user existe
        $user = User::where('username', $credentials['username'])
                ->where('active', true)
                ->where(function ($query) use ($credentials) {
                    $query->whereNull('blocked_until')->orWhere('blocked_until', '<', now());
                })
                ->whereNotNull('email_verified_at')
                ->WhereNull('deleted_at')
                ->first();

        // verificar
        if(!$user) {
            return back()->withInput()->with([
                'invalid_login' => 'Login enválido'
            ]);
        }

        // verificar se a password e valida
        if(!password_verify($credentials['password'], $user->password)) {
            return back()->withInput()->with([
                'invalid_login' => 'Login enválido'
            ]);
        }

        // atualizar o ultimo login (last_login)
        $user->last_login_at = now();
        $user->blocked_until = null;
        $user->save();

        // login propriamente dito!
        $request->session()->regenerate();
        Auth::login($user);

        // redirecionar
        return redirect()->intended(route('home'));
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function store_user(Request $request): void
    {
        //  form validacao
        $request->validate(
            [
                'username' => 'required|min:4|max:30|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|max:30|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'username.required' => 'campo :attribute e obrigatorio',
                'username.unique' => 'o :attribute ja existe por outro Usuário ',
                'username.min' => 'Nome no campo :attribute precisa ter pelo menos :min caracteres ',
                'username.max' => 'Nome no campo :attribute precisa ter no maximo :max caracteres ',
                'email.required' => 'campo :attribute e obrigatorio',
                'email.email' => 'O :attribute deve ser um endereço de :attribute Valido',
                'email.unique' => 'o :attribute ja existe por outro Usuário ',
                'password.required' => 'campo :attribute e obrigatorio',
                'password.min' => ' O :attribute precisa ter pelo menos :min numero ',
                'password.max' => 'O :attribute precisa ter no maximo :max numero ',
                'password.regex' => 'A :attribute deve conter pelo menos uma letra maiúscula, uma letra minúscula, e um numero ',
                'password_confirmation.required' => 'O campo :attribute e obrigatorio',
                'password_confirmation.same' => 'A confirmação da senha não corresponde igual a senha .',
            ]
        );

        // criar um novo usuário definindo um token de verificação de email
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->token = Str::random(64);

        dd($user);
        // redirecionar
        //return redirect()->intended(route('home'));
    }

    public function logout(): RedirectResponse {
        // logout
        Auth::logout();
        return redirect()->route('login');
    }

}
