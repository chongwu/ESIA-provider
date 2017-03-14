<?php

namespace Chongwu\Esia\Controllers;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'esiaLogout']);
    }

    public function redirectToEsia()
    {
        return \Socialite::with('esia')->redirect();
    }

    public function handleEsiaCallback()
    {
        try{
            $user = \Socialite::driver('esia')->user();
        }
        catch (\Exception $e){
            return redirect('login/esia');
        }
        $authUser = $this->findOrCreateUser($user);

        \Auth::login($authUser, true);

        return redirect('home');
    }

    private function findOrCreateUser($user)
    {
        if($authUser = User::where('esia_id', $user->id)->first()){
            return $authUser;
        }
        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'esia_id' => $user->id
        ]);
    }

    public function esiaLogout(Request $request)
    {
        return \Socialite::driver('esia')->logout();
    }
}
