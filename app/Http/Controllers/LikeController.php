<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        $input = $request->json()->all();
        try {
            $amv = AMV::findOrFail($input['amv_id']);

            $like = Like::create([
                'amv_id' => $amv->id,
                'user_id' => $request->user()->id
            ]);

            return response()->json($like, 201);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'AMV could not be found'], 404);
        }
   
    }

    /**
     * Remove the specified Like from database
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $input = $request->json()->all();
        try {
            $like = Like::findOrFail($id);
            $like->delete();
            return response()->json('{}', 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Like could not be found'], 404);
        }
    }
}
