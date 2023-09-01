<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    protected $client;
    protected $usersToken;

    public function __construct()
    {
        $this->usersToken = new \App\Models\RsiaUsersToken();
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost/rsiapi/api/',
            'timeout' => 2.0,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
        ]);
    }

    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'password' => 'required',
        ]);


        // try to /api/auth/room/login
        try {
            $response = $this->client->request('POST', 'auth/room/login', [
                'json' => [
                    'username' => $request->get('id_user'),
                    'password' => $request->get('password'),
                ]
            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
        }

        $responseBodyAsString = $response->getBody()->getContents();
        $responseBodyAsObject = json_decode($responseBodyAsString);

        if ($responseBodyAsObject->success) {
            // pass token to request 
            
            // set auth
            session()->put('token', $responseBodyAsObject->access_token);
            session()->put('username', $request->get('id_user'));
            
            return redirect()->route('index'); 
        } else {
            return back()->with('loginError', 'Login Gagal');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
