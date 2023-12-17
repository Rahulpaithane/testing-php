<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $logo='disa_testboard_logo.png';
        // $logo='testBoard_logo.jpeg';
        // $title='testBoard';
        $title='Dishaonlinetest';

        View::composer('frontend*', function($view) use ($logo, $title)
        { 
            $view->with('shared', ['logo'=>$logo, 'title'=>$title]);
        });

        View::composer('backend*', function($view) use ($logo, $title)
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

            // session(['routePath' => $routePath, 'domainRoute' =>$domainRoute]);
            // Session::put('routePath', $routePath);
            // Session::put('domainRoute', $domainRoute);

            // $request->merge(['domainRoute' => $domainRoute]);

            // dd($request->domainRoute);

            $view->with('shared', ['routePath' => $routePath, 'domainRoute'=>$domainRoute, 'logo'=>$logo, 'title'=>$title]);
        });
    }
}
