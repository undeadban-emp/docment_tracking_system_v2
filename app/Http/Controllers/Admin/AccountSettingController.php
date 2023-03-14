<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admins\Admin;
use Illuminate\Support\Facades\Validator;
use App\Rules\MatchOldPassword;
use App\Traits\ProfilePicture;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{
    use ProfilePicture;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'                  =>      ['sometimes', 'required', 'string', 'max:255'],
            'email'                 =>      ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:admins,email,' . Auth::id()],
            'username'              =>      ['sometimes', 'required', 'string', 'max:255', 'unique:admins,username' . Auth::id()],
            'current_password'      =>      ['sometimes', 'required', new MatchOldPassword],
            'password'              =>      ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function showAccountPage()
    {
        $user = Admin::find(Auth::id());
        return view('admin.account-settings', compact('user'));
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
        $user  = Admin::find(Auth::id());
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return $user;
    }

    protected function updateProfilePicture(array $data)
    {
        $filename = $this->uploadProfilePicture($data['profile_picture']);

        $user = Admin::find(Auth::id());
        $user->profile_picture = $filename;
        $user->save();

        return $user;
    }

    protected function updateUsername(array $data)
    {
        $user  = Admin::find(Auth::id());
        $user->username = $data['username'];
        $user->save();

        return $user;
    }

    protected function updatePassword(array $data)
    {
        $user  = Admin::find(Auth::id());
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }
}
