<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenancyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = request()->getHost();
        $hostParts = explode('.', $host);

        if (count($hostParts) ==3) {
            //   $subdomain = $hostParts[0];
            //   $domain = $hostParts[1];
            $routePath='tenantAdmin';
            $domainRoute=false;
        
        } else if(count($hostParts) ==2){
            if($hostParts[1] == 'com' || $hostParts[1] == 'in'){
                    $routePath='admin';
                    $domainRoute=true;
            } else {
                $routePath='tenantAdmin';
                $domainRoute=false;
            }
        } else {
            $routePath='admin';
            $domainRoute=true;
        }

        $request->merge(['routePath' => $routePath, 'domainRoute' =>$domainRoute]);
        return $next($request);
    }
}
