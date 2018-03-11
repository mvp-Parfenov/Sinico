<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Entity\User;
use App\Http\Controllers\Controller;
use RegisterService;

class RegisterController extends Controller
{

    /**
     * @var RegisterService
     */
    private $registerService;

    public function __construct(
        RegisterService $registerService
    )
    {
        $this->middleware('guest');
        $this->registerService = $registerService;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $this->registerService->register($request);

        return redirect()->route('login')
            ->with('success', 'Check your email and click on the link to verify.');
    }

    public function verify($token)
    {
        if (!$user = User::where('verify_token', $token)->first()) {
            return redirect()->route('login')
                ->with('error', 'Sorry your link cannot be identified.');
        }
        try {
            $this->registerService->verify($user->id);

            return redirect()->route('login')->with('success', 'Your e-mail is verified. You can now login.');
        } catch (\DomainException $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }
}