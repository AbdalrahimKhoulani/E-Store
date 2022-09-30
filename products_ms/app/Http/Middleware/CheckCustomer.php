<?php

namespace App\Http\Middleware;

use App\Traits\HostNames;
use Closure;
use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckCustomer
{
    use HostNames;
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
        ->get($this->getUsers_MS().'/check/is-customer');

        if($response->status()==200){
        return $next($request);
        }
        return new JsonResponse([
            'status'=>false,
            'message'=>'Unauthorized'
        ],401);    }
}
