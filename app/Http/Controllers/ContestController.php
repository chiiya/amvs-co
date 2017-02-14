<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserRoleRequest;
use App\Services\ContestService;

class ContestController extends Controller
{

    public function index(ContestService $service)
    {
        return response()->json($service->index(), 200);
    }

    public function show(ContestService $service, $id)
    {
        $contest = $service->get($id);
        if (!$contest) return response()->json(['error' => 'Contest could not be found'], 404);
        return response()->json($contest, 200);
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy(ContestService $service, Request $request, $id)
    {
        $result = $service->destroy($request, $id);
        if (!is_int($result)) return response()->json("{}", 200);
        switch($result) {
            case 401:
                return response()->json(['error' => "Unauthorized. Not the owner of this Contest."], 401);
            case 404:
                return response()->json(['error' => 'Contest could not be found'], 404);
            default:
                return response()->json(['error' => 'Server Error'], 500);
        }
    }

/**
* Contributor Controllers
* -----------------------------------------------------------------------------------------------------
*/

    public function getUsers(ContestService $service, $id)
    {
        $users = $service->getUsers($id);
        if (!$users) return response()->json(['error' => 'Contest could not be found'], 404);
        return response()->json($users, 200);
    }

    /**
     * Store a new contributor (creator/judge) for a contest
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUser(UserRoleRequest $request, ContestService $service)
    {
        $contest = $service->storeUser($request);
        if (!is_int($contest)) return response()->json($contest, 201);
        switch($contest) {
            case 401:
                return response()->json(['error' => "Unauthorized. Not the owner of this Contest."], 401);
            case 404:
                return response()->json(['error' => 'Contest or User could not be found'], 404);
            default:
                return response()->json(['error' => 'Server Error'], 500);
        }
    }


    /**
     * Delete a contributor from a contest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyUser(ContestService $service, Request $request, $id, $userId)
    {
        if (!$role = $request->get('role'))
            return response()->json(['error' => 'Please specify the role of the user you wish to delete.'], 400);
        $result = $service->destroyUser($request, $id, $userId, $role);
        if (!is_int($result)) return response()->json("{}", 20);
        switch($result) {
            case 401:
                return response()->json(['error' => "Unauthorized. Not the owner of this Contest."], 401);
            case 404:
                return response()->json(['error' => 'Contest or User could not be found'], 404);
            default:
                return response()->json(['error' => 'Server Error'], 500);
        }
    }


}
