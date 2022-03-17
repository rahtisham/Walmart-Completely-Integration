<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WalmartMarketPlace;

class AdminController extends Controller
{
    public  function index()
    {
        $NumberOfUsers = User::count();
        $NumberOfMarketPlace = WalmartMarketPlace::count();
        return view('admin.index' , ['NumberOfUsers' => $NumberOfUsers , 'NumberOfMarketPlace' => $NumberOfMarketPlace]);

    }
}
