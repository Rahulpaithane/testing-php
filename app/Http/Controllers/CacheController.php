<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;

class CacheController extends Controller
{
    public function caches(){
        Artisan::call('config:cache');
        echo "Config Cached </br>";
        Artisan::call('route:cache');
        echo "Route Cached </br>";
        Artisan::call('optimize');
        echo "Optimized";
    }
    public function clearCaches(){
        Artisan::call('config:clear');
        echo "Config Cleared </br>";
        Artisan::call('route:clear');
        echo "Route Cleared </br>";
        Artisan::call('cache:clear');
        echo "Cache Cleared </br>";
        Artisan::call('view:clear');
        echo "view Cleared </br>";
        Artisan::call('optimize:clear');
        echo "Optimized with clear";
    }
    public function runQueue(){
        Artisan::call('queue:work');
        echo "queue running..";
    }
     public function restartQueue(){
        Artisan::call('queue:restart');
        echo "queue restarted!";
    }
}
