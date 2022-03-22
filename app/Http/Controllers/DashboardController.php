<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function home()
    {
        if(auth()->user()){

            if(auth()->user()->roles == 1)
            {
                return redirect('user/marketplace');
            }
            if(auth()->user()->roles == 2)
            {
                return redirect('dashboard/admin');
            }
            if(auth()->user()->roles == 3)
            {
                return redirect('password/update-password');
            }
            if(auth()->user()->roles == 4)
            {
                return redirect('subscription/plan');
            }

        }else{
            return redirect('/login');
        }

    }

    public function redirection()
    {
        if(auth()->user()){

            if(auth()->user()->roles == 1)
            {
                return redirect('user/marketplace');
            }
            if(auth()->user()->roles == 2)
            {
                return redirect('dashboard/admin');
            }
            if(auth()->user()->roles == 3)
            {
                return redirect('password/update-password');
            }
            if(auth()->user()->roles == 4)
            {
                return redirect('subscription/plan');
            }

        }else{
            return redirect('/login');
        }
    }

    public function admin()
    {
        if(auth()->user()){

            if(auth()->user()->roles == 2)
            {
                return redirect('dashboard/admin');
            }

        }else{
            return redirect('/login');
        }
    }



    public function client()
    {
      return view('client');
    }
}
