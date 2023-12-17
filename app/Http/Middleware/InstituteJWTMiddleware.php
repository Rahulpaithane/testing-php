<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\JWTException;

class InstituteJWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        try {
            $user = Auth::guard('institute')->user();
            
            $users = JWTAuth::parseToken()->authenticate();
            // dd($user);
        } catch (TokenExpiredException $e) {
            // Generate a new token
            // $newToken = JWTAuth::refresh();

            // // Pass the current response with the new token
            // $response = $next($request);
            // $response->headers->set('Authorization', 'Bearer ' . $newToken);
    
            // return $response;
            // return $next($request);
            return response()->json(['error' => 'Token expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json(['error' => 'Token blacklisted'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token absent'], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
