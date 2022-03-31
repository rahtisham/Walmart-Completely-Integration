<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\updatePassword;
use App\Models\WalmartMarketPlace;
use App\Models\PaymentLogs;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user.user-view' , ['users' => $users]);
    }

    public function show()
    {
        return view('update_password.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        $updatedPassword = User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

        if($updatedPassword)
        {
            $user_session_id = auth()->user()->id;
            $updateRoleId = User::where('id' , $user_session_id)->update(['roles' => '1']);
            return redirect('/login')->with(['success' => 'Your Password Has Been Updated !']);
        }


    }


    public function userProfile($id)
    {

        $user = User::where('id' , $id)->first();
        $marketplace = WalmartMarketPlace::where('user_id' , $id)->first();
        $paymentLogs = PaymentLogs::where('user_id' , $id)->get();

        return view('admin.user.user-profile' , ['user' => $user , 'paymentLogs' => $paymentLogs , 'marketplace' => $marketplace]);

    }

}
