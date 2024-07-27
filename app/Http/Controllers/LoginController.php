<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    // protected $client;
    // protected $headers;

    public function __construct()
    {
        // $this->headers = [
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json'
        // ];

        // $this->client = new \GuzzleHttp\Client([
        //     'base_uri' => ENV('API_URL'),
        //     'timeout' => 120,
        //     'headers' => $this->headers,
        // ]);
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

        $user = User::where('username', $request->get('id_user'))
            ->where('password', md5($request->get('password')))->first();

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect('/');
        } else {
            return back()->with('loginError', 'Login Gagal');
        }


        // try to /api/auth/room/login
        // try {
        //     $response = $this->client->request('POST', 'auth/room/login', [
        //         'json' => [
        //             'username' => $request->get('id_user'),
        //             'password' => $request->get('password'),
        //         ],
        //     ]);
        // } catch (\GuzzleHttp\Exception\BadResponseException $e) {
        //     $response = $e->getResponse();
        // }

        // $responseBodyAsString = $response->getBody()->getContents();
        // $responseBodyAsObject = json_decode($responseBodyAsString);
        // $code = $response->getStatusCode();

        // if ($code == 200) {
        //     // pass token to request 
        //     $this->headers['Authorization'] = 'Bearer ' . $responseBodyAsObject->access_token;

        //     // set auth
        //     session()->put('token', $responseBodyAsObject->access_token);
        //     session()->put('username', $request->get('id_user'));

        //     return redirect()->route('index'); 
        // } else {
        //     return back()->with('loginError', 'Login Gagal');
        // }
    }
    public function logout(Request $request)
    {
        // if ($request->session()->has('token')) {
        //     $this->headers['Authorization'] = 'Bearer ' . $request->session()->get('token');

        //     try {
        //         $response = $this->client->request('POST', 'auth/room/logout', [
        //             'headers' => $this->headers,
        //         ]);
        //     } catch (\GuzzleHttp\Exception\BadResponseException $e) {
        //         $response = $e->getResponse();
        //     }

        //     $responseBodyAsString = $response->getBody()->getContents();
        //     $responseBodyAsObject = json_decode($responseBodyAsString);

        //     if ($responseBodyAsObject->success) {
        //         session()->forget('token');
        //         session()->forget('username');
        //     }
        // }

        // Auth::guard('web')->logout();

        Auth::logout();
        
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
