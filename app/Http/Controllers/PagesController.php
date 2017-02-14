<?php

namespace App\Http\Controllers;
use App\Services\AMVService;
use App\Services\UserService;
use Auth;
use Illuminate\Http\Request;

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
     * Show login page
     *
     * @return \Illuminate\Http\Response
     */
    public function showLogin(AMVService $service)
    {
        if (Auth::check()) return redirect()->route('dashboard');
        $amvs = $service->index($user = null, $paginated = true);
        return view('login', [
            'amvs' => $amvs
        ]);
    }

    /**
     * Show signup page
     *
     * @return \Illuminate\Http\Response
     */
    public function showSignup()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('signup');
    }

    /**
     * Show details page of specified AMV by url parameters
     *
     * @param  String $name: username
     * @param  String $amv: url-property of the AMV (e.g. 'An Etude' -> 'an_etude')
     * @return \Illuminate\Http\Response
     */
    public function showAMV(UserService $userService, AMVService $amvService, $name, $amvUrl)
    {
        $user = $userService->getByName($name);
        if (!$user) return view('404');
        $amv = $amvService->find($user->id, $amvUrl);
        if (!$amv) return view('404');

        $likedByUser = false;
        // If user is logged in, check if he has liked the AMV
        if (Auth::check()) {
            $likedByUser = $amvService->likedByUser($amv->id, Auth::user()->id);
        }
        if ($amv->published) {
            return view('amv', [
                'amv' => $amv,
                'user' => $user,
                'likedByUser' => $likedByUser
            ]);
        }
        return view('404');
    }

    /**
     * Show profile page of the requested user
     *
     * @param  String $name: username
     * @return \Illuminate\Http\Response
     */
    public function showUser(UserService $userService, AMVService $amvService, $name)
    {
        $user = $userService->getByName($name);
        if (!$user) return view('404');
        $amvs = $amvService->index($user->id);
        if (!$amvs) return view('404');

        $latest = $amvs->first();
        return view('profile', [
            'user' => $user,
            'amvs' => $amvs,
            'latest' => $latest
        ]);
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
