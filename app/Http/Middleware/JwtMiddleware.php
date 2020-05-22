<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $request->currentUser = JWTAuth::parseToken()->authenticate();
            if ($request->currentUser->blocked) {
                $data['statusCode'] = 400;
                $data['message'] = 'User is blocked';
                $data['result'] = null;
                return response()->json($data, $data['statusCode']);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $data['statusCode'] = 400;
                $data['message'] = 'Token is Invalid';
                $data['result'] = null;
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $data['statusCode'] = 400;
                $data['message'] = 'Token is Expired';
                $data['result'] = null;
            } else {
                $data['statusCode'] = 400;
                $data['message'] = 'Authorization Token not found';
                $data['result'] = null;
            }
            return response()->json($data, $data['statusCode']);
        }
        return $next($request);
    }
}
