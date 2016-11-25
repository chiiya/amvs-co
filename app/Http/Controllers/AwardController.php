<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Award;
use App\AMV;

class AwardController extends Controller
{
    
    /**
     * Store a new Award instance in database.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->json()->all();
        try {
            $amv = AMV::findOrFail($input['amv_id']);
            if ($amv->user_id !== $request->user()->id) {
                return response()
                    ->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
            }

            $award = Award::create([
                'award' => $input['award'],
                'amv_id' => $amv->id,
                'contest_id' => $input['contest_id']
            ]);

            return response()->json($award, 201);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'AMV could not be found'], 404);
        }
   
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->json()->all();
        try {
            $award = Award::findOrFail($id);
            $amv = AMV::findOrFail($input['amv_id']);
            if ($amv->user_id !== $request->user()->id) {
                return response()
                    ->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
            }

            $award->award = $input['award'];
            $award->save();

            $award->contest = $input['contest'];

            return response()->json($award, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'AMV could not be found'], 404);
        }

    }

    /**
     * Remove the specified Award from database
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $input = $request->json()->all();
        try {
            $award = Award::findOrFail($id);
            $amv = AMV::findOrFail($award->amv_id);
            if ($amv->user_id !== $request->user()->id) {
                return response()
                    ->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
            }

            $award->delete();
            return response()->json('{}', 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'AMV or Award could not be found'], 404);
        }
    }
}
