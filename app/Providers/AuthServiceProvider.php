<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('users_token', function (\Illuminate\Http\Request $request) {
            $token = $request->session()->get('token');
            $username = $request->session()->get('username');
            if ($token &&  $username) {
                $client = new \GuzzleHttp\Client([
                    'base_uri' => env('API_URL'),
                    'timeout' => 2.0,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token
                    ],
                ]);

                try {
                    $resp = $client->post('auth/room/validate');
                } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                    $resp = $e->getResponse();
                }

                $respBodyAsString = $resp->getBody()->getContents();
                $respBodyAsObject = json_decode($respBodyAsString);

                if ($respBodyAsObject->success) {
                    $user = User::where('username', $username)->first();
                    
                    Auth::setUser($user);
                    $request->session()->regenerate();

                    return $user;
                }
            }
        });
    
    }
}
