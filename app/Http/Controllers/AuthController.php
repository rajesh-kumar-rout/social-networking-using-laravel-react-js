<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function account(Request $request)
    {
        return response()->json(['user' => $request->user() ? $request->user() : null]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:40',
            'last_name' => 'required|max:40',
            'email' => 'required|email|max:40|unique:users,email',
            'password' => 'required|min:6|max:20',
            'bio' => 'required|max:255',
            'birth_date' => 'nullable|date',
            'work' => 'nullable|max:40',
            'current_city' => 'nullable|max:40',
            'home_town' => 'nullable|max:40',
            'school' => 'nullable|max:40',
            'college' => 'nullable|max:40',
            'relationship' => 'nullable|max:40',
            'gender' => 'nullable|max:40',
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:4000'
        ]);

        $user = new User;

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->bio = $request->bio;
        $user->birth_date = $request->birth_date;
        $user->work = $request->work;
        $user->current_city = $request->current_city;
        $user->home_town = $request->home_town;
        $user->school = $request->school;
        $user->college = $request->college;
        $user->relationship = $request->relationship;
        $user->gender = $request->gender;

        if($request->profile_image)
        {
            $user->profile_image_url = url('/uploads') . '/' . $request->profile_image->store('images/users', 'public');
        }

        if($request->cover_image)
        {
            $user->cover_image_url = url('/uploads') . '/' . $request->cover_image->store('images/users', 'public');
        }

        $user->save();

        $token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        return response()->json(['token' => $token]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!($user && Hash::check($request->password, $user->password))) 
        {
            return response()->json(['error' => 'Invalid email or password'], 422);
        }

        $token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        return response()->json(['token' => $token]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|current_password',
            'new_password' => 'required|min:6|max:20'
        ]);
 
        $request->user()->password = Hash::make($request->new_password);

        $request->user()->save();

        return response()->json(['success' => 'Password changed successfully']);
    }

    public function editProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:40',
            'last_name' => 'required|max:40',
            'email' => 'required|email|max:40|unique:users,email,' . $request->user()->id,
            'bio' => 'required|max:255',
            'birth_date' => 'nullable|date',
            'work' => 'nullable|max:40',
            'current_city' => 'nullable|max:40',
            'home_town' => 'nullable|max:40',
            'school' => 'nullable|max:40',
            'college' => 'nullable|max:40',
            'relationship' => 'nullable|max:40',
            'gender' => 'nullable|max:40',
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:4000'
        ]);

        $user = $request->user();

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->birth_date = $request->birth_date;
        $user->work = $request->work;
        $user->current_city = $request->current_city;
        $user->home_town = $request->home_town;
        $user->school = $request->school;
        $user->college = $request->college;
        $user->relationship = $request->relationship;
        $user->gender = $request->gender;

        if($request->profile_image)
        {
            if($user->profile_image_url)
            {
                $profile_image_url = str_replace(url('/uploads'), '', $user->profile_image_url);

                Storage::disk('public')->delete($profile_image_url);        
            }

            $user->profile_image_url = url('/uploads') . '/' . $request->profile_image->store('images/users', 'public');
        }

        if($request->cover_image)
        {
            if($user->cover_image_url)
            {
                $cover_image_url = str_replace(url('/uploads'), '', $user->cover_image_url);

                Storage::disk('public')->delete($cover_image_url);        
            }

            $user->cover_image_url = url('/uploads') . '/' . $request->cover_image->store('images/users', 'public');
        }

        $user->save();

        return response()->json($user);
    }

    public function logout(Request $request)
    {
        auth()->logout();
 
        return response()->json(['success' => 'Logout successfully']);
    }
}
