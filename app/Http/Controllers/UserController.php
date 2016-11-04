<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\AMV;
use App\Http\Requests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| UserController
|--------------------------------------------------------------------------
|
| This User Controller handles all resource requests on users with
| authenticated sessions. For stateless requests see the Api\UserController
|
*/
class UserController extends Controller
{

    /**
     * Display the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) 
    {
        try {
            $user = User::findOrFail($id);
            // If the user is requesting his own data, make email field visible
            if (Auth::check() && $request->user()->id == $id) {
                return response()->json($user->makeVisible('email'), 200);
            }
            return response()->json($user, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["User could not be found."], 404);
        }
    }
    
    /**
     * Store a new User instance in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            ->header('Location', '/users/'.$user->id);
    }

    /**
     * Update an existing User entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) 
    {
        $user = User::findOrFail($id);

        // Check if user is authorized to update the requested user.
        if ($request->user()->id != $id) {
            return response()
                ->json(['error' => "Unauthorized."], 401);
        }

        // If a _new_ email has been provided, fully validate it.
        if($request->email !== $user->email) {
            $this->validate($request, [
                'email' => 'required|unique:users|email'
            ]);
        } else {
             $this->validate($request, [
                'avatar' => 'image|mimes:jpeg,png,jpg|max:150'
            ]);
        }

        // If a new password has been provided, hash it and update user.
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // If an avatar has been uploaded, store it and link it to user.
        if ($request->hasFile('avatar')) {
            $prefix = strtolower($user->name) . '_';
            $filename = uniqid($prefix).'.'.$request->avatar->extension();
            $request->avatar->move(public_path('images'), $filename);
            $oldavatar = public_path() . $user->avatar;
            unlink($oldavatar);
            $user->avatar = '/images/' . $filename;
        }

        // Update all other properties
        $input = $request->except(['avatar', 'password']);
        $user->fill($input);

        // Save new user object to DB
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Delete an existing user from database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        if ($user->id !== $request->user()->id) {
            return response()
                ->json(['error' => "Unauthorized."], 401);
        }

        $user->delete();
        return response()->json("{}", 200);
    }
}
