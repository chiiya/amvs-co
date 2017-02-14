<?php
/**
 * Created by PhpStorm.
 * User: allaire
 * Date: 12.02.17
 * Time: 16:00
 */

namespace App\Services;

use App\AMV;
use App\Award;
use App\Http\Requests\AMVRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AMVService
{
    protected $amv;
    protected $award;

    public function __construct(AMV $amv, Award $award)
    {
        $this->amv = $amv;
        $this->award = $award;
    }

    /**
     * Get a listing of all AMVs, paginated in chunks of 9
     * @TODO allow browsing of further pages
     * @return mixed
     */
    public function index($user = null, $paginated = false, $unpublished = false)
    {
        $query = $this->amv
            ->orderBy('created_at', 'DESC')
            ->with('user', 'genres', 'awards.contest')
            ->withCount('likes');

        if ($user)
            $query = $query->where('user_id', $user);
        if (!$unpublished)
            $query = $query->where('published', true);
        if ($paginated)
            return $query->simplePaginate(9);
        return $query->get();
    }

    /**
     * Get a specific AMV by its id
     * @param $id
     * @return \App\AMV
     */
    public function get($id, $complete = false, $check = false, $request = null)
    {
        try {
            $query = $this->amv->where('id', $id);
            if ($complete)
                $query = $query->with('user', 'genres', 'awards.contest')->withCount('likes');
            $amv = $query->firstOrFail();
            // Check if user is authorized to perform the action
            if ($check && !$request->user()->can('view', $amv)) return 401;
            return $amv;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function getAward($id)
    {
        try {
            $award = $this->award->findOrFail($id);
            return $award;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    /**
     * Find a specific AMV via different filtering options
     * @param null $user_id
     * @param null $url
     * @param null $title
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function find($user_id = null, $url = null, $title = null)
    {
        try {
            $query = $this->amv
                ->with('user', 'genres', 'awards')
                ->withCount('likes');
            if ($user_id)
                $query = $query->where('user_id', $user_id);
            if ($url)
                $query = $query->where('url', $url);
            if ($title)
                $query = $query->where('title', $title);
            return $query->firstOrFail();

        } catch (ModelNotFoundException $e) {
            return false;
        }

    }

    /**
     * Check if an AMV is liked by a specified user
     * @param $amvId
     * @param $userId
     * @return bool
     */
    public function likedByUser($amvId, $userId)
    {
        return DB::table('likes')
                ->where('amv_id', $amvId)
                ->where('user_id', $userId)
                ->count() > 0;
    }

    /**
     * Let a user like an AMV
     * @param $amvId
     * @param $userId
     * @return bool
     */
    public function like($amvId, $userId)
    {
        $amv = $this->get($amvId);
        if (!$amv) return 404;
        if ($this->likedByUser($amvId, $userId)) return true;
        $amv->likes()->attach($userId);
        return true;
    }

    /**
     * Let a user unlike an AMV
     * @param $amvId
     * @param $userId
     * @return bool
     */
    public function unlike($amvId, $userId)
    {
        $amv = $this->get($amvId);
        if (!$amv) return 404;
        // Check if AMV is already unliked
        if (!$this->likedByUser($amvId, $userId)) return true;
        // Else proceed with unlike
        $amv->likes()->detach($userId);
        return true;
    }

    /**
     * Store a new AMV in the database
     * @param $request
     * @return static
     */
    public function store($request)
    {
        // Create a new AMV model with the input data that doesn't need to be further processed
        $amv = $this->amv->create([
            'title' => $request->title,
            'animes' => $request->animes,
            'music' => $request->music,
            'description' => ($request->description === 'null') ? null : $request->description,
            'video' => ($request->video === 'null') ? null : $request->video,
            'videoHost' => ($request->videoHost === 'null') ? null : $request->videoHost,
            'videoCode' => ($request->videoCode === 'null') ? null : $request->videoCode,
            'download' => ($request->download === 'null') ? null : $request->download,
            'driveId' => ($request->driveId === 'null') ? null : $request->driveId,
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
        return $amv;
    }

    public function update($request, $id)
    {
        $amv = $this->get($id);
        if (!$amv) return 404;
        // Check if user is authorized to perform the action
        if (!$request->user()->can('update', $amv)) return 401;

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
            $oldfile = public_path() . $amv->poster;
            if ($amv->poster && file_exists($oldfile)) unlink($oldfile);
            $amv->poster = '/images/' . $filename;
        }

        // If a background has been uploaded, store it and link it to the AMV.
        if ($request->hasFile('bg')) {
            $prefix = $amv->url . '_bg_';
            $filename = uniqid($prefix).'.'.$request->bg->extension();
            $request->bg->move(public_path('images'), $filename);
            $oldfile = public_path() . $amv->bg;
            if ($amv->bg && file_exists($oldfile)) unlink($oldfile);
            $amv->bg = '/images/' . $filename;
        }

        // If a list of genres has been specified, link them to the AMV.
        if ($request->genres) {
            $genres = json_decode($request->genres);
            $amv->genres()->sync($genres);
        }

        $input = $request->except(['published', 'poster', 'genres', 'bg', 'user_id']);
        foreach ($input as $field) {
            if ($field === 'null') $field = null;
        }
        $amv->fill($input);
        $amv->save();

        return $amv;
    }

    public function destroy($request, $id)
    {
        $amv = $this->get($id);
        if (!$amv) return 404;
        // Check if user is authorized to perform the action
        if (!$request->user()->can('delete', $amv)) return 401;

        $amv->delete();
        return true;
    }

    public function storeAward($request)
    {
        $input = $request->json()->all();
        $amv = $this->get($input['amv_id']);
        if (!$amv) return 404;
        // Check if user is authorized to perform the action
        if (!$request->user()->can('update', $amv)) return 401;

        $award = $this->award->create([
            'award' => $input['award'],
            'amv_id' => $amv->id,
            'contest_id' => $input['contest_id']
        ]);
        return $award;
    }

    public function updateAward($request, $id)
    {
        $input = $request->json()->all();
        $amv = $this->get($input['amv_id']);
        $award = $this->getAward($id);
        if (!$amv || !$award) return 404;

        // Check if user is authorized to perform the action
        if (!$request->user()->can('update', $amv)) return 401;

        $award->award = $input['award'];
        $award->save();
        $award->contest = $input['contest'];

        return $award;
    }

    public function destroyAward($request, $id)
    {
        $award = $this->getAward($id);
        if (!$award) return 404;
        $amv = $this->get($award->amv_id);
        if (!$amv) return 404;
        // Check if user is authorized to perform the action
        if (!$amv || !$request->user()->can('update', $amv)) return 401;

        $award->delete();
        return true;
    }

}