<?php

namespace App\Http\Controllers;

use Auth;
use App\Menux;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    function home()
    {
        try
        {
            $user = Auth::user();
    
            

            $data = array();

            return view('dashboard');
            
        }
        catch (\Exception $e)
        {
            Menux::logError(__FILE__, __LINE__, __METHOD__, $e);
        }
    }
}
