<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Http\Requests;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function find(Request $request)
    {
        // If a name is submitted via query parameter, search for that name
        if ($name = $request->get('name')) {
            try {
                $user = User::where('name', $name)->firstOrFail();
                return response()->json($user, 200);
            } catch (ModelNotFoundException $e) {
                return response()->json(["A user with that username doesn't exist."], 404);
            }
        } else {
            return response()->json(["Please filter by username."], 400);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["User could not be found."], 404);
        }
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
