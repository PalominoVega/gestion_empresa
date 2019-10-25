<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;

class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next, $guard = null)
    {
        $token=$request->header('Authorization');
        $user=User::where('api_token',$token)->first();
        if ($user==null) {
            return response()->json(["status"=>"ERROR","data"=>"Token no existe"]);
        }
        $request->merge(['empresa_id'=>$user->empresa_id,'user_id'=>$user->id,'empresa' => $user->empresa]);
        session(['empresa_id' => $user->empresa_id]);
        session(['empresa' => $user->empresa]);
        session(['user_id' => $user->id]);
        return $next($request);
    }
}
