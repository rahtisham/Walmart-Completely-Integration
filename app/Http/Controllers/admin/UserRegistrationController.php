<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;

class UserRegistrationController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required', 'alpha', 'max:255',
            'email' => 'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',
            'lname' => ['required', 'alpha', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'alpha', 'max:255'],
            'postal' => ['required', 'max:7'],
            'country' => ['required', 'alpha', 'max:255'],
            'state' => ['required', 'alpha', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'password' => 'required|string|min:6|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'password_confirmation' => 'required',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
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
            'password.required' => 'Password is required',
            'password_confirmation.required' => 'Confirm password is required',
        ])->validate();

        $userData = [
            'name' => $request['fname'],
            'email' => $request['email'],
            'last_name' => $request['lname'],
            'address' => $request['address'],
            'city' => $request['city'],
            'postal' => $request['postal'],
            'country' => $request['country'],
            'state' => $request['state'],
            'contact' => $request['contact'],
            'password' => Hash::make($request['password']),
        ];

        $user = User::store($userData);

        return redirect()->back()->with(['success' => 'Your Appeal Lab Account Has Been Created !']);
    }
}
