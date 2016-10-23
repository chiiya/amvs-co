<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AMV;
use App\Contest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;

class AMVController extends Controller
{
    public function show($name, $amv)
    {
        try {
            $user = User::where('name', $name)->firstOrFail();
            $amv = AMV::where('user_id', $user->id)->where('url', $amv)->with('user')->firstOrFail();
            $amv->contests->toArray();
            $amv->genres->toArray();
            return view('amv', [
                'amv' => $amv,
                'user' => $user
            ]);
        } catch (ModelNotFoundException $e) {
            return view('404');
        }
    }

    public function updateAwards(Request $request, $amvId) 
    {
        $contests = $request->input('contests');
        $amv = AMV::find($amvId);
        if ($amv->user_id !== $request->user()->id) {
            return response()
                ->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
        }

        foreach ($contests as $contest) {
            if ($contest['award_id']) {
                $amv->contests()->newPivotStatement()
                    ->where('id', $contest['award_id'])->update(['award' => $contest['award']]);
            } else {
                $amv->contests()->attach($contest['id'], ['award' => $contest['award']]);
            }
        }

        return response()->json(["message" => "AMV updated."], 200);
    }

    public function deleteAward(Request $request, $amvId, $awardId) {
        $amv = AMV::find($amvId);
        if ($amv->user_id !== $request->user()->id) {
            return response()
                ->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
        }

        $amv->contests()->newPivotStatement()->where('id', $awardId)->delete();

        return response()->json(["message" => "AMV updated."], 200);
    }
}
