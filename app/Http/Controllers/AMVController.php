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

    public function store(Request $request) 
    {
        // Validate request input data
        $this->validate($request, [
            'title' => 'required',
            'music' => 'required',
            'animes' => 'required',
            'poster' => 'image|mimes:jpeg,png,jpg|max:500',
            'bg' => 'image|mimes:jpeg,png,jpg|max:500',
            'video' => 'url',
            'download' => 'url'
        ]);

        // Create a new AMV model with the input data that doesn't need to be further processed
        $amv = AMV::create([
            'title' => $request->title,
            'animes' => $request->animes,
            'music' => $request->music,
            'description' => $request->description,
            'video' => $request->video,
            'videoHost' => $request->videoHost,
            'videoCode' => $request->videoCode,
            'download' => $request->download,
            'driveId' => $request->driveId,
            'published' => false,
            'user_id' => $request->user()->id
        ]);


        $published = $request->input('published');
        if ($published === 'true') $published = true;
        else $published = false;

        $amv->published = $published;

        // If a poster has been uploaded, store it and link it to the AMV.
        if ($request->hasFile('poster')) {
            $prefix = $amv->url . '_';
            $filename = uniqid($prefix).'.'.$request->poster->extension();
            $request->poster->move(public_path('images'), $filename);
            $amv->poster = '/images/' . $filename;
        }

        // If a background has been uploaded, store it and link it to the AMV.
        if ($request->hasFile('bg')) {
            $prefix = $amv->url . '_bg_';
            $filename = uniqid($prefix).'.'.$request->bg->extension();
            $request->bg->move(public_path('images'), $filename);
            $amv->bg = '/images/' . $filename;
        }

        // If a list of genres has been specified, link them to the AMV. 
        if ($request->genres) {
            $genres = json_decode($request->genres);
            foreach($genres as $genre) {
                $amv->genres()->attach($genre);
            }
        }

        // Save new AMV object to DB
        $amv->save();
        $user = User::find($request->user()->id);
        $amv->user = $user;
    
        return response()->json($amv, 200);
    }

    public function destroy(Request $request, $id)
    {
        $amv = AMV::find($id);
        if ($amv->user_id !== $request->user()->id) {
            return response()
                ->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
        }

        $amv->delete();
        return response()->json("{}", 200);
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
            if (array_key_exists('award_id', $contest)) {
                $amv->contests()->newPivotStatement()
                    ->where('id', $contest['award_id'])->update(['award' => $contest['award']]);
            } else {
                $amv->contests()->attach($contest['id'], ['award' => $contest['award']]);
            }
        }

        return response()->json($amv->contests, 200);
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
