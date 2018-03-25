<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Entity\User;
use Auth;
use Dotenv\Exception\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use function redirect;

class LoginController extends Controller
{


    use ThrottlesLogins;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    // юзер аунтифицировался
//    protected function authenticated(Request $request, $user)
//    {
//        if($user->status !== User::STATUS_ACTIVE){
//            $this->guard()->logout();
//            return (
//                back()->with('error', 'You need to confirm your account. Please check your email.')
//            );
//        }
//
//        return redirect()->intended($this->redirectPath());
//    }

    public function login(LoginRequest $request)
    {
        if($this->hasTooManyLoginAttempts($request)){
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $authenticate = Auth::attempt(
            $request->only(['email', 'password']),
            $request->filled('remember')
        );

        if($authenticate) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            $user = Auth::user();

            if($user->status !== User::STATUS_ACTIVE){
                Auth::logout();
                return
                back()->with(
                    'error', 'You need to confirm your account. Please check your email.'
                );
            }

            return redirect()->intended(route('cabinet.home'));
        }

        $this->incrementLoginAttempts($request);
        throw ValidationException::withMessages(['email' => [trans('auth.failed')]]);
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('home');
    }

    protected function username()
    {
        return 'email';
    }
}
