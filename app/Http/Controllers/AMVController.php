<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AMV;
use App\Contest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;

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
    public function index(Request $request)
    {
        if (!$user = $request->get('user')) 
            return response()->json(['error' => 'Please filter by user ID and optionally AMV title.'], 400);
        
        // If AMV title is passed as query parameter, search for AMV with that title
        if ($title = $request->get('title')) {
            try {
                $amv = AMV::where('user_id', $user)
                    ->where('title', $title)
                    ->with('user', 'genres', 'awards.contest')
                    ->firstorFail();
                return response()->json($amv, 200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'An AMV with that title could not be found.'], 404);
            }
        }

        // Otherwise return all AMVs of specified user
        $amvs = AMV::where('user_id', $user)
            ->with('user', 'genres', 'awards.contest')
            ->get();
        return response()->json($amvs, 200);
    }

    /**
     * Display the specified AMV.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        try {
            $amv = AMV::where('id', $id)->with('user', 'genres', 'awards.contest')->firstOrFail();
            return response()->json($amv, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'AMV could not be found'], 404);
        }
    }

    /**
     * Store a new AMV instance in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        // JSON boolean is only read as a string :(, need to check further
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
    public function update(Request $request, $id)
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
        $amv = AMV::where('id', $id)->first();
        
        if ($amv->user_id !== $request->user()->id) {
            return response()
                ->json(['error' => "Unauthorized. Not the owner of this AMV."], 401);
        }

        $published = $request->input('published');
        // JSON boolean is only read as a string :(, need to check further
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
            // First, detach all old genres
            $amv->genres()->detach();
            // Then attach the newly specified ones
            $amv->genres()->attach($genres);
        }

        $input = $request->except(['published', 'poster', 'genres', 'bg', 'user_id']);
        $amv->fill($input);
        $amv->save();
    
        return response()->json($amv, 200);
    }

    /**
     * Delete an existing AMV from database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
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
    
}
