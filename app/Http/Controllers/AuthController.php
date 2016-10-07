<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    private $client;

    public function __construct()
    {
        $this->client = DB::table('oauth_clients')->where('id', 2)->first();
    }
    
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticate(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $request->request->add([
            'username' => $email,
            'password' => $password,
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => '*'
        ]);

        $tokenRequest = Request::create(
            env('APP_URL').'/oauth/token',
            'post'
        );
        return Route::dispatch($tokenRequest);
    }

}