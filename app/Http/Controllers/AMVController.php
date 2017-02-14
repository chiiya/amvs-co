<?php

namespace App\Http\Controllers;

use App\Services\AMVService;
use App\Http\Requests\AMVRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| AMVController
|--------------------------------------------------------------------------
|
| This AMV Controller handles all resource requests on AMVs with
| authenticated sessions. For stateless requests see the Api\AMVController
|
*/

class AMVController extends Controller
{
    /**
     * Display a listing of the most recent/popular AMVs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(AMVService $service, Request $request)
    {
        if (!$user = $request->get('user')) 
            return response()->json(['error' => 'Please filter by user ID.'], 400);

        // If user is requesting his own AMVs, return unpublished ones too
        if (Auth::check() && $request->user()->id == $user) {
            return response()->json($service->index($user, false, true), 200);
        }
        // Else only published ones
        return response()->json($service->index($user), 200);
    }

    /**
     * Display the specified AMV.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(AMVService $service, Request $request, $id)
    {
        if ($request->get('check') && $request->get('check') === 'true') {
            $amv = $service->get($id, true, true, $request);
            if (!$amv) return response()->json(['error' => 'AMV could not be found'], 404);
            if (is_int($amv) && $amv === 401) return response()->json(['error' => 'Unauthorized. Not the owner of this AMV.'], 401);
            return response()->json($amv, 200);
        } else {
            $amv = $service->get($id, true);
            if (!$amv) return response()->json(['error' => 'AMV could not be found'], 404);
            return response()->json($amv, 200);
        }
    }

    /**
     * Store a new AMV instance in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AMVRequest $request, AMVService $amvService, UserService $userService)
    {
        $amv = $amvService->store($request);
        $amv->user = $userService->get($request->user()->id);
        return response()
            ->json($amv, 201)
            ->header('Location', '/amvs/'.$amv->id);
    }

    /**
     * Update an existing AMV entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AMVRequest $request, AMVService $service, $id)
    {

        $amv = $service->update($request, $id);
        if (!is_int($amv)) return response()->json($amv, 200);
        switch($amv) {
            case 401:
                return response()->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
            case 404:
                return response()->json(['error' => 'AMV could not be found'], 404);
            default:
                return response()->json(['error' => 'Server Error'], 500);
        }
    }

    /**
     * Delete an existing AMV from database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, AMVService $service, $id)
    {
        $result = $service->destroy($request, $id);
        if (!is_int($result)) return response()->json("{}", 200);
        switch($result) {
            case 401:
                return response()->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
            case 404:
                return response()->json(['error' => 'AMV could not be found'], 404);
            default:
                return response()->json(['error' => 'Server Error'], 500);
        }
    }
    
}
