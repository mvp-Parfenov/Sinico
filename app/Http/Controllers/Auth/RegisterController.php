<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerifyMail;
use App\Entity\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Mail;
use function redirect;
use \Illuminate\Http\Request;
use Validator;

class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function verify($token)
    {

        $user = User::where('verify_token', $token)->first();

        if(!$user){
            return (
                redirect()
                    ->route('login')
                ->with('error', 'Sorry your lin cannot be identified.')
            );
        }

        if($user->status !== User::STATUS_WAIT){
            return (
                redirect()
            ->route('login')
                ->with('error', 'Your email is already verified')
            );
        }

        $user->status = User::STATUS_ACTIVE;
        $user->verify_token = null;
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Your e-mail is verified. You can now login.');
    }


    // User::make([]) $user->save()
    // User::create([]) -- сразу сохраняет
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'verify_token' => Str::random(),
            'status' => User::STATUS_WAIT,
        ]);

        Mail::to($user->email)
            ->send(new VerifyMail($user));

        event(new Registered($user));



        return redirect()
            ->route('login')
            ->with('success', 'Check your email and click on the link to verify.');

    }
}
