<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AMV;
use App\Contest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;

class AMVController extends Controller
{
    public function index(Request $request)
    {
        if ($userId = $request->get('user')) {
            // If AMV title is passed as query parameter, search for AMV with that title
            if ($title = $request->get('title')) {
                try {
                    $amv = AMV::where('user_id', $userId)
                        ->where('title', $title)
                        ->with('user')
                        ->firstOrFail();
                    $amv->contests->toArray();
                    return response()->json($amv, 200);
                } catch (ModelNotFoundException $e) {
                    return response()->json(["An AMV with that title could not be found."], 404);
                }
            }

            // Otherwise return all AMVs of specified user
            $amvs = AMV::where('user_id', $userId)->with('user')->get();
            foreach ($amvs as $amv) {
                $amv->contests->toArray();
            }
            return response()->json($amvs, 200);
        }

        return response()->json(["Please filter by user id and optionally amv title."], 400);

    }

    public function show($id)
    {
        try {
            $amv = AMV::with('user')->findOrFail($id);
            $amv->contests->toArray();
            return response()->json($amv, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["AMV could not be found."], 404);
        }
    }

    public function store(Request $request)
    {
        $amv = AMV::create([
            'title' => $request->input('title'),
            'genre' => $request->input('genre'),
            'animes' => $request->input('animes'),
            'music' => $request->input('music'),
            'description' => $request->input('description'),
            'user_id' => $request->input('user_id'),
            'video' => $request->input('video')
        ]);

        if ($contests = $request->input('contests')) {
            foreach ($contests as $contest) {
                $con = Contest::firstOrCreate(['name' => $contest['name'], 'year' => $contest['year']]);
                $amv->contests()->attach($con->id, ['award' => $contest['award']]);
            }
        }

        $amv->contests->toArray();
        return response()
            ->json($amv, 201)
            ->header('Location', '/api/users/'.$amv->user_id.'/amvs/'.$amv->id);
    }

    public function update(Request $request, $id)
    {
        try {
            $amv = AMV::with('user')->findOrFail($id);

            $amv->title = $request->input('title');
            $amv->genre = $request->input('genre');
            $amv->animes = $request->input('animes');
            $amv->music = $request->input('music');
            $amv->description = $request->input('description');
            $amv->video = $request->input('video');

            if ($contests = $request->input('contests')) {
                foreach ($contests as $contest) {
                    if ($contest['id'] && $amv->contests()->where('contest_id', $contest['id'])->count() > 0) {
                        $amv->contests()->updateExistingPivot($contest['id'], ['award' => $contest['award']]);
                    } else {
                        $con = Contest::firstOrCreate(['name' => $contest['name'], 'year' => $contest['year']]);
                        $amv->contests()->attach($con->id, ['award' => $contest['award']]);
                    }
                }
            }

            $amv->save();

        } catch (ModelNotFoundException $e) {
            return response()->json(["AMV does not exist."], 404);
        }

        $amv->contests->toArray();
        return response()
            ->json($amv, 200);
    }

    public function destroy($id) {
        try {
            $amv = AMV::findOrFail($id);
            $amv->delete();
            return response()->json(["AMV deleted."], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["AMV could not be found."], 404);
        }
    }
}
