<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
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

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    /**
     * Display the specified user.
     * METHOD: GET
     *
     * @param   \Illuminate\Http\Request  $request
     * @param   \App\Services\UserService $service
     * @param   int $id
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, UserService $service, $id)
    {
        $user = $service->get($id);
        if (!$user) return response()->json(["User could not be found."], 404);

        // If user is requesting his own information, make hidden fields visible
        if (Auth::check() && $request->user()->id == $user->id) {
            return response()->json($user->makeVisible('email'), 200);
        }
        return response()->json($user, 200);
    }

    /**
     * Get the currently authenticated user.
     * METHOD: GET
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function auth(Request $request) 
    {   
        $user = $request->user();
        return response()->json($user->makeVisible('email'), 200);
    }
    
    /**
     * Store a new User instance in database.
     * METHOD: POST
     *
     * @param   \App\Http\Requests\UserRequest  $request
     * @param   \App\Services\UserService $service
     * @return  \Illuminate\Http\Response
     */
    public function store(UserRequest $request, UserService $service)
    {
        $user = $service->store($request->all());
        return response()
            ->json($user, 201)
            ->header('Location', '/users/'.$user->id);
    }

    /**
     * Update an existing User entry.
     * METHOD: PUT
     *
     * @param   \App\Http\Requests\UserRequest   $request
     * @param   \App\Services\UserService $service
     * @param   int $id
     * @return  \Illuminate\Http\Response
     */
    public function update(UserRequest $request, UserService $service, $id)
    {
        $user = $service->update($request, $id);
        if (!is_int($user)) return response()->json($user, 200);
        switch($user) {
            case 401:
                return response()->json(['error' => "Unauthorized."], 401);
            case 404:
                return response()->json(['error' => 'User could not be found'], 404);
            default:
                return response()->json(['error' => 'Server Error'], 500);
        }
    }

    /**
     * Delete an existing user from database.
     * METHOD: DELETE
     *
     * @param   \App\Http\Requests\UserRequest   $request
     * @param   \App\Services\UserService $service
     * @param   int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Request $request, UserService $service, $id)
    {
        $result = $service->destroy($request, $id);
        if (!is_int($result)) return response()->json("{}", 200);
        switch($result) {
            case 401:
                return response()->json(['error' => "Unauthorized."], 401);
            case 404:
                return response()->json(['error' => 'User could not be found'], 404);
            default:
                return response()->json(['error' => 'Server Error'], 500);
        }
    }

    /**
     * Get an index of all contests the user is part of, and his respective roles.
     * METHOD: GET
     *
     * @param   \App\Services\UserService $service
     * @param   int $id
     * @return  \Illuminate\Http\Response
     */
    public function contestIndex(UserService $service, $id)
    {
        $contests = $service->getContests($id);
        return response()->json($contests, 200);
    }
}
