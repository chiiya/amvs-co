<?php

namespace App\Http\Controllers;

use App\Services\AMVService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Like;
use App\AMV;

use App\Http\Requests;

class LikeController extends Controller
{
    /**
     * Store a new Like instance in database.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AMVService $service)
    {
        $input = $request->json()->all();
        $result = $service->like($input['amv_id'], $request->user()->id);
        if (is_int($result) && $result === 404) return response()->json(['error' => 'AMV could not be found'], 404);
        return response()->json("{}", 201);
    }

    /**
     * Remove the specified Like from database
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, AMVService $service, $id)
    {
        $result = $service->unlike($id, $request->user()->id);
        if (is_int($result) && $result === 404) return response()->json(['error' => 'AMV could not be found'], 404);
        return response()->json("{}", 200);
    }
}
