<?php

namespace App\Http\Controllers;

use Auth;
use App\Menux;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    function dashboard()
    {
        try
        {
            $user = Auth::user();
    
            if(!$user->Store)
            {
                return redirect('dashboard/estabelecimento');
            }

            $data = array();

            return view('request');
            
        }
        catch (\Exception $e)
        {
            Menux::logError(__FILE__, __LINE__, __METHOD__, $e);
        }
    }
}
