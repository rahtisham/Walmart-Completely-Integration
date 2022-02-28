<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorizeNetController extends Controller
{
    public function index()
    {
        return view('authorize.index');
    }
}
