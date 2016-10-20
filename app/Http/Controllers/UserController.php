<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\AMV;
use App\Http\Requests;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{

    public function show($name)
    {
        try {
            $user = User::where('name', $name)->firstOrFail();
            $amvs = AMV::where('user_id', $user->id)->with('user')->get();
            foreach ($amvs as $amv) {
                $amv->contests->toArray();
            }
            $latest = $amvs->last();
            return view('profile', [
                'user' => $user,
                'amvs' => $amvs,
                'latest' => $latest
            ]);
        } catch (ModelNotFoundException $e) {
            return view('404');
        }
    }

    public function showDashboard(Request $request) 
    {
        return view('dashboard', [
            'user' => $request->user()
        ]);
    }

    public function showProfile(Request $request) {
        return 'Logged in as ' . $request->user()->name;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:users|alpha_dash|max:20',
            'email' => 'required|unique:users|email',
            'password' => 'required'
        ]);
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
        return response()
            ->json($user, 201)
            ->header('Location', '/api/users/'.$user->id);
    }
}
