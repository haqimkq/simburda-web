<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Helpers\Date;
use App\Models\AksesBarang;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\Gudang;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        $barang = Barang::select(DB::raw('*, count(nama) as jumlah'))->filter(request(['search','orderBy','filter','filter-gudang']))->groupBy('nama')->paginate(12)->withQueryString();
        return view('barang.index',[
            'barangs' => $barang,
            'authUser' => $user,
            'gudangs' => Gudang::get(),
            'countUndefinedAkses' => $countUndefinedAkses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('barang.create',[
            'gudangs' => Gudang::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->jenis == "TIDAK_HABIS_PAKAI"){
            $validate = $request->validate([
                'nama' => 'required',
                'jenis' => 'required',
                'kondisi' => 'required',
                'gudang_id' => 'required',
                'merk' => 'required',
                'detail' => 'required',
                'keterangan' => 'nullable',
                'gambar' => 'nullable',
            ]);
        }else{
            $validate = $request->validate([
                'nama' => 'required',
                'jenis' => 'required',
                'gudang_id' => 'required',
                'merk' => 'required',
                'detail' => 'required',
                'jumlah' => 'required',
                'ukuran' => 'required',
                'satuan' => 'required',
                'gambar' => 'nullable',
            ]);
        }
        if($request->file('gambar')){
            $validate['gambar'] = $request->file('gambar')->store('assets/barang', 'public');
        }
        $barang = Barang::create($validate);
        $validate['barang_id'] = $barang->id;
        if($request->jenis == "TIDAK_HABIS_PAKAI"){
            BarangTidakHabisPakai::create($validate);
        }else{
            BarangHabisPakai::create($validate);
        }
        return redirect('barang')->with('createBarangSuccess','Berhasil Menambahkan Barang ('.$barang->nama.')');
    }

    public function tambahSeriBaru($nama)
    {
        $barang = Barang::where('nama', $nama)->latest()->first();
        $data = [
            'gambar' => $barang->gambar,
            'jenis' => $barang->jenis,
            'nomor_seri' => $barang->nomor_seri+1,
            'nama' => $barang->nama,
            'alamat' => $barang->alamat,
            'latitude' => $barang->latitude,
            'longitude' => $barang->longitude,
            'satuan' => $barang->satuan,
            'detail' => $barang->detail,
            'excerpt' => Str::words($barang->detail,10)
        ];
        $barang = Barang::create($data);
        if($barang->jenis == 'tidak habis pakai'){
            $image = QrCode::size(1280)->format('png')->errorCorrection('H')->generate($barang->id);
            $output_file = 'assets/qr-code/['.$barang->nomor_seri. ']-' . $barang->nama . '.png';
            Storage::disk('public')->put($output_file, $image);
            Barang::where('id', $barang->id)->update(['qrcode' => $output_file]);
        }
        return redirect()->back()->with('createBarangSuccess', 'Berhasil Menambahkan Barang'.$barang->nama);   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        
        // $barangDalamPinjaman = 
        // $historyPeminjamanBarang = Meminjam::with('barang','user','proyek', 'suratJalan','proyek.proyekManager')
        //     ->where('barang_id', $barang->id)
        //     ->where('dipinjam', 1)
        //     ->orderBy('tgl_berakhir', 'DESC')
        //     // ->whereHas('proyek', fn($q) => $q->where('selesai',1))
        //     ->paginate(2)->withQueryString();

        return view('barang.detail',[
            'barang' => $barang,
            // 'barangPinjaman' => $barangDalamPinjaman,
            // 'historyPeminjamanBarang' => $historyPeminjamanBarang,
        ]);
    }

    public function showNamaBarang($nama)
    {
        $barang = Barang::where('nama',$nama)->where('nomor_seri', 1)->first();
        $allBarang = Barang::where('nama',$nama)->orderBy('nomor_seri')->get();
        $jumlahBarang = Barang::where('nama',$nama)->count();
        $barangTersedia = Barang::where('nama',$nama)->where('tersedia',1)->count();
        return view('barang.seri',[
            'barang' => $barang,
            'jumlahBarang' => $jumlahBarang,
            'barangTersedia' => $barangTersedia,
            'allBarang' => $allBarang,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        if($barang->jenis == "TIDAK_HABIS_PAKAI"){
            $detail = BarangTidakHabisPakai::where('barang_id', $barang->id)->first();
        }else{
            $detail = BarangHabisPakai::where('barang_id', $barang->id)->first();
        }
        return view('barang.edit',[
            'gudangs' => Gudang::all(),
            'barang' => $detail
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->jenis == "TIDAK_HABIS_PAKAI"){
            $validate = $request->validate([
                'nama' => 'required',
                'kondisi' => 'required',
                'gudang_id' => 'required',
                'merk' => 'required',
                'detail' => 'required',
                'keterangan' => 'nullable',
                'gambar' => 'nullable',
            ]);
        }else{
            $validate = $request->validate([
                'nama' => 'required',
                'gudang_id' => 'required',
                'merk' => 'required',
                'detail' => 'required',
                'jumlah' => 'required',
                'ukuran' => 'required',
                'satuan' => 'required',
                'gambar' => 'nullable',
            ]);
        }
        if($request->file('gambar')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validate['gambar'] = $request->file('gambar')->store('assets/barang', 'public');
        }

        if($request->jenis == "HABIS_PAKAI"){
            $data['jumlah'] = $validate['jumlah'];
            $data['ukuran'] = $validate['ukuran'];
            $data['satuan'] = $validate['satuan'];
            BarangHabisPakai::where('barang_id',$id)->update($data);
        }else{
            $dataTidakHabis['kondisi'] = $validate['kondisi'];
            $dataTidakHabis['keterangan'] = $validate['keterangan'];
            BarangTidakHabisPakai::where('barang_id',$id)->update($dataTidakHabis);
        }
        unset($validate['jumlah']);
        unset($validate['ukuran']);
        unset($validate['satuan']);
        unset($validate['kondisi']);
        unset($validate['keterangan']);
        Barang::where('id',$id)->update($validate);

        return redirect()->route('barang')->with('createBarangSuccess', 'Berhasil Merubah Barang '.$request->nama); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $barang = Barang::find($id);
        if($barang->jenis == "TIDAK_HABIS_PAKAI"){
            $detail = BarangTidakHabisPakai::where('barang_id', $barang->id)->first();
            BarangTidakHabisPakai::destroy($detail->id);
        }else{
            $detail = BarangHabisPakai::where('barang_id', $barang->id)->first();
            BarangHabisPakai::destroy($detail->id);
        }
        if($barang->gambar){
            Storage::delete($barang->gambar);
        }
        Barang::destroy($id);
        return redirect('/barang')->with('deleteBarangSuccess','Berhasil Menghapus Barang ('.$barang->nama.')');
    }
}
