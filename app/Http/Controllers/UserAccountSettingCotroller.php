<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Rules\MatchOldPassword;
use App\Rules\PhoneNumber;
use App\Traits\ProfilePicture;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserAccountSettingCotroller extends Controller
{
    use ProfilePicture;

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname'                     =>      ['sometimes', 'required', 'string', 'max:255'],
            'middlename'                    =>      ['sometimes', 'nullable', 'string', 'max:255'],
            'lastname'                      =>      ['sometimes', 'required', 'string', 'max:255'],
            'suffix'                        =>      ['sometimes', 'nullable', 'string', 'max:255'],
            'position'                      =>      ['sometimes', 'required'],
            'office'                        =>      ['sometimes', 'required'],
            'phone_number'                  =>      ['sometimes', 'required', new PhoneNumber],
            'profile_picture'               =>      ['sometimes', 'mimes:png,jpg,jpeg', 'max:2048'],
            'email'                         =>      ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'current_password'              =>      ['sometimes', 'required', new MatchOldPassword],
            'password'                      =>      ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function showUserAccountPage()
    {
        $user = User::find(Auth::id());
        return view('user.account-settings', compact('user'));
    }

    public function update(Request $request)
    {
        if ($request->has('type')) {
            $this->validator($request->all())->validate();

            if ($request->type === "profile") {
                $this->updateProfile($request->all());
                $message = 'Profile was updated successfully.';
            }

            if ($request->type === "profile-picture") {
                $this->updateProfilePicture($request->all());
                $message = 'Profile picture was updated successfully.';
            }

            if ($request->type === "username") {
                $this->updateUsername($request->all());
                $message = 'Username was updated successfully.';
            }

            if ($request->type === "password") {
                $this->updatePassword($request->all());
                $message = 'Password was updated successfully.';
            }

            return redirect()->back()->with('success', $message);
        }
    }

    protected function updateProfile(array $data)
    {
        $user  = User::find(Auth::id());
        $user->firstname = $data['firstname'];
        $user->middlename = $data['middlename'];
        $user->lastname = $data['lastname'];
        $user->suffix = $data['suffix'];
        $user->position = $data['position'];
        $user->office = $data['office'];
        // $user->role = $data['accountRole'];
        $user->phone_number = $data['phone_number'];
        $user->save();

        return $user;
    }

    public function updateProfilePicture(array $data)
    {
        $filename = $this->uploadProfilePicture($data['profile_picture']);

        $user = User::find(Auth::id());
        $user->profile_picture = $filename;
        $user->save();

        return $user;
    }

    protected function updateUsername(array $data)
    {
        $user  = User::find(Auth::id());
        $user->username = $data['username'];
        $user->save();

        return $user;
    }

    protected function updatePassword(array $data)
    {
        $user  = User::find(Auth::id());
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }
}
