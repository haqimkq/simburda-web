<?php

namespace App\Http\Controllers;

use App\Helpers\Location;
use App\Models\AksesBarang;
use App\Models\Barang;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $authUser = Auth::user();
        // $this->authorize('admin');
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();

        $userRole = User::select(DB::raw("COUNT(*) as count, role"))
        ->groupBy('role')
        ->pluck('count', 'role');

        $proyek = Proyek::select(DB::raw("COUNT(*) as count, DATE_FORMAT(created_at, '%b %Y') as date"))
                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M %Y')"))
                    ->orderBy('created_at')
                    ->pluck('count', 'date');

                    $proyekSelesai = Proyek::select(DB::raw("COUNT(*) as count, DATE_FORMAT(created_at, '%b %Y') as date"))
                    ->where('selesai', 1)
                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M %Y')"))
            ->orderBy('created_at')
            ->pluck('count', 'date');
            $proyekBelumSelesai = Proyek::select(DB::raw("COUNT(*) as count, DATE_FORMAT(created_at, '%b %Y') as date"))
            ->where('selesai', 0)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M %Y')"))
            ->orderBy('created_at')
            ->pluck('count', 'date');
        // if (Gate::allows('admin')) {
            //     return view('home',[
                //         'user' => $user,
                //         'allUser' => $user->nama,
                //     ]);
                // }
                    
        // if ($role == 'admin') {
        // }
        // dd($userRole);
        // $image = QrCode::size(1280)->format('png')->errorCorrection('H')->generate('973981273h3724y24y82734y');
        return view('home',[
            'authUser' => $authUser,
            'userRoleLabels' => $userRole->keys(),
            'proyekLabels' => $proyek->keys(),
            'labelsProyekBS' => $proyekBelumSelesai->keys(),
            'labelsProyekS' => $proyekSelesai->keys(),
            'userRole' => $userRole->values(),
            'proyek' => $proyek->values(),
            'proyekSelesai' => $proyekSelesai,
            'proyekBelumSelesai' => $proyekBelumSelesai,
            'countUndefinedAkses' => $countUndefinedAkses,
        ]);
        // return view('home');
    }
    public function test($imageId){
        $filePath = asset($imageId);
        return response()->file($filePath);
    }
}
