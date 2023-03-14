<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserService;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userID = Auth::user()->id;
        $user = User::with(['documents'])->find($userID);
        return view('home');
    }
}
