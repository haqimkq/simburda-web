<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        
        // $role = auth()->user()->role;
        $authUser = Auth::user();
        $allUser = User::all();

        $supervisor = $allUser->where('role', '=', 'supervisor');
        $projectmanager = $allUser->where('role', '=', 'project manager');
        $logistic = $allUser->where('role', '=', 'logistic');
        $admingudang = $allUser->where('role', '=', 'admin gudang');
        $purchasing = $allUser->where('role', '=', 'purchasing');
        $user = $allUser->where('role', '=', 'user');
        // $this->authorize('admin');

        // if (Gate::allows('admin')) {
        //     return view('home',[
        //         'user' => $user,
        //         'allUser' => $user->nama,
        //     ]);
        // }

        // if ($role == 'admin') {
        // }
        return view('home',[
            'authUser' => $authUser,
            'allUser' => $allUser,
            'supervisor' => $supervisor,
            'projectmanager' => $projectmanager,
            'logistic' => $logistic,
            'admingudang' => $admingudang,
            'purchasing' => $purchasing,
            'user' => $user,
        ]);
        // return view('home');
    }
}
