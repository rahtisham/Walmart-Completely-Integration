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

    public function userEdit(Request $request , $id)
    {
        $user = User::where('id' , $id)->first();
        return view('admin.user.edit' , ['user' => $user]);
    }

    public function edit(Request $request , $id)
    {
        $validator = validator()->make($request->all() , [
            'fname' => 'required', 'alpha', 'max:255',
            'email' => 'required|email|max:255',
            'lname' => ['required', 'alpha', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'alpha', 'max:255'],
            'postal' => ['required', 'max:7'],
            'country' => ['required', 'max:255'],
            'state' => ['required', 'alpha', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'roles' => ['required', 'max:255'],
        ],[
            'email.required' => 'Email is required',
            'fname.required' => 'First name is required',
            'fname.alpha' => 'First name must only contain letters',
            'lname.required' => 'First name is required',
            'lname.alpha' => 'Last name must only contain letters',
            'address.required' => 'Address is required',
            'city.required' => 'City is required',
            'state.required' => 'State is required',
            'contact.required' => 'Phone number is required',
            'postal.required' => 'Postal code is required',
            'country.required' => 'Country is required',
            'roles.required' => 'Roles is required',
        ])->validate();

        $updated = [
            'name' => $request['fname'],
            'email' => $request['email'],
            'last_name' => $request['lname'],
            'address' => $request['address'],
            'city' => $request['city'],
            'roles' => $request['roles'],
            'postal' => $request['postal'],
            'country' => $request['country'],
            'state' => $request['state'],
            'contact' => $request['contact'],
        ];

        $user = User::userUpdate($updated , $id);

         return redirect()->back()->with('success' , 'User Has Been Updated Successfully');

    }

}
