<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;

class CacheController extends Controller
{
    public function index(){

        Artisan::call('optimize:clear');
        
        return redirect('/')->with('message','Clear Cache Success');
    }
}
