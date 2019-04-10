<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show a user edit page
     *
     *
     * @return \Illuminate\View\View
     */
    public function editProfile()
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update our user information
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            "password" => "required",
            "confirm_password" => "required|same:password"
        ]);
        $user = User::findOrFail($request->user_id);
        $input = $request->only('password');
        $input['password'] = Hash::make($input['password']);
        $user->update($input);

        return redirect('admin/dashboard')->withMessage(trans('quickadmin::admin.users-controller-successfully_updated'));
    }
}
