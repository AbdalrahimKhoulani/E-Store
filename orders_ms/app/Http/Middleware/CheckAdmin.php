<?php

namespace App\Http\Middleware;

use App\Traits\Helper;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckAdmin
{
    use Helper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = Http::withToken($request->bearerToken())
        ->withHeaders([
            'Accept' => 'application/json'
        ])
        ->get($this->getUsers_MS().'/check/is-admin');

        if($response->status()==200){
        return $next($request);
        }
        return new JsonResponse([
            'status'=>false,
            'message'=>'Unauthorized'
        ],401);
    }
}
