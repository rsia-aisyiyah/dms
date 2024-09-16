<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateTokenJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        dd("kanggo gak");
        $client = new \GuzzleHttp\Client();
        $token = $request->bearerToken();
        if($token) {
            $user = \App\Models\RsiaUsersToken::where('token', $token)->first();
            if ($user) {
                // try {
                //     $response = $client->request('POST', 'auth/room/validate', [
                //         'header' => [
                //             'Accept' => 'application/json',
                //             'Content-Type' => 'application/json',
                //             'Authorization' => 'Bearer ' . $token,
                //         ],
                //     ]);
                // } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                //     $response = $e->getResponse();
                // }

                // $responseBodyAsString = $response->getBody()->getContents();
                // $responseBodyAsObject = json_decode($responseBodyAsString);

                // if ($responseBodyAsObject->success) {
                //     // find user in table rsia_users_token
                //     $user = \App\Models\RsiaUsersToken::where('identifier', $user->identifier)->first();
                //     if ($user) {
                //         $user->token = $responseBodyAsObject->access_token;
                //         $user->where('identifier', $user->identifier)->update([
                //             'token' => $responseBodyAsObject->access_token,
                //         ]);
                //     } else {
                //         $user = new \App\Models\RsiaUsersToken();
                //         $user->identifier = $responseBodyAsObject->user->id_user;
                //         $user->token = $responseBodyAsObject->access_token;
                //         $user->save();
                //     }

                    return $next($request);
                // } else {
                //     return redirect()->route('login');   
                // }
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect('/login');
        }
    }
}
