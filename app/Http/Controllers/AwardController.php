<?php

namespace App\Http\Controllers;

use App\Services\AMVService;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    
    /**
     * Store a new Award instance in database.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AMVService $service, Request $request)
    {
        $award = $service->storeAward($request);
        if (!is_int($award)) return response()->json($award, 201);
        switch($award) {
            case 401:
                return response()->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
            case 404:
                return response()->json(['error' => 'AMV could not be found'], 404);
            default:
                return response()->json(['error' => 'Server Error'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AMVService $service, Request $request, $id)
    {
        $award = $service->updateAward($request, $id);
        if (!is_int($award)) return response()->json($award, 200);
        switch($award) {
            case 401:
                return response()->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
            case 404:
                return response()->json(['error' => 'AMV could not be found'], 404);
            default:
                return response()->json(['error' => 'Server Error'], 500);
        }
    }

    /**
     * Remove the specified Award from database
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AMVService $service, Request $request, $id)
    {
        $result = $service->destroyAward($request, $id);
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
