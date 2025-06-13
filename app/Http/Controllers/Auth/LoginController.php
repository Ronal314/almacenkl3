<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/dashboard';

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

    public function username()
    {
        return 'ci';  // Cambiar 'ci' por el nombre del campo que desees
    }

    public function login(Request $request)
    {
        $messages = [
            'ci.required' => 'El campo Usuario es obligatorio.',
            'password.required' => 'El campo Contraseña es obligatorio.',
        ];

        $validator = Validator::make($request->all(), [
            'ci' => 'required|string',
            'password' => 'required|string',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('ci', 'remember'));
        }

        $remember = $request->has('remember');

        if (!Auth::attempt($request->only('ci', 'password'), $remember)) {
            return redirect()->back()
                ->withErrors(['password' => 'El usuario o la contraseña son incorrectos.'])
                ->withInput($request->only('ci', 'remember'));
        }

        $request->session()->put('welcomeShown', false);

        return $this->sendLoginResponse($request);
    }
}
