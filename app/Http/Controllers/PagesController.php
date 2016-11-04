<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\AMV;
use App\User;


/*
|--------------------------------------------------------------------------
| PagesController
|--------------------------------------------------------------------------
|
| The PagesController handles all requests that return views.
|
*/
class PagesController extends Controller
{

    /**
     * Show details page of specified AMV by url parameters
     *
     * @param  String $name: username
     * @param  String $amv: url-property of the AMV (e.g. 'An Etude' -> 'an_etude')
     * @return \Illuminate\Http\Response
     */
    public function showAMV($name, $amv)
    {
        try {
            $user = User::where('name', $name)->firstOrFail();
            $amv = AMV::where('user_id', $user->id)->where('url', $amv)->with('user', 'genres', 'awards')->firstOrFail();
            if ($amv->published) {
                return view('amv', [
                    'amv' => $amv,
                    'user' => $user
                ]);
            }
            return view('404');       
        } catch (ModelNotFoundException $e) {
            return view('404');
        }
    }

    /**
     * Show profile page of the requested user
     *
     * @param  String $name: username
     * @return \Illuminate\Http\Response
     */
    public function showUser($name)
    {
        try {
            $user = User::where('name', $name)->firstOrFail();
            $amvs = AMV::where('user_id', $user->id)->where('published', true)->with('user', 'genres', 'awards')->get();
            $latest = $amvs->last();
            return view('profile', [
                'user' => $user,
                'amvs' => $amvs,
                'latest' => $latest
            ]);
        } catch (ModelNotFoundException $e) {
            return view('404');
        }
    }

    /**
     * Show user dashboard of currently authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showDashboard(Request $request) 
    {
        return view('dashboard', [
            'user' => $request->user()
        ]);
    }
}
