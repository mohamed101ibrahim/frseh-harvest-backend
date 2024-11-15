<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
   
     protected function redirectTo($request)
     {
         if (! $request->expectsJson()) {
             return response()->json(['message' => 'You are not authenticated'], 401);
         }
     }
 
     public function handle($request, \Closure $next, ...$guards)
     {
         try {
             $this->authenticate($request, $guards);
         } catch (\Illuminate\Auth\AuthenticationException $e) {
            
             return response()->json(['message' => 'You are not authenticated'], 401);
         }
 
         return $next($request);
     }
 
    
        
     protected function unauthenticated($request, array $guards)
     {
         if ($request->expectsJson()) {
             return response()->json(['message' => 'You are not authenticated.'], 401);
         }
 
         parent::unauthenticated($request, $guards);  
     }
}