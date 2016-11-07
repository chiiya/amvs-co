<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    use AuthenticatesUsers, ThrottlesLogins;

    private $client;
    protected $username = 'name';

}