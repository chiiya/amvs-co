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
}
