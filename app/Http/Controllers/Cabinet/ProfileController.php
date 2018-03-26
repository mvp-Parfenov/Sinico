<?php

namespace App\Http\Controllers\Cabinet;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function redirect;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        return view('cabinet.profile.home', compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();

        return view('cabinet.profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|regex:/^\d+$/s',
        ]);

        $user = Auth::user();
//        $user->update($request->only('name', 'last_name'));
        $oldPhone = $user->phone;

        $user->update($request->only('name', 'last_name', 'phone'));

        if($user->phone !== $oldPhone){
            $user->unverifyPhone();
        }

        return redirect()->route('cabinet.profile.home');
    }
}
