<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Lokasi;
use App\Models\Item;
use App\Models\Produksi;
use App\Components\Helpers;

class ProduksiController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $tanggal = ($request->has('tanggal')) ? $request->tanggal : date('Y-m-d');
        $lokasi = ($request->has('lokasi')) ? $request->lokasi : '';
        $list_lokasi = Lokasi::all();

        $query = DB::table('produksis AS p')
            ->select('p.id', 'p.tanggal_transaksi', 'p.qty_actual', 'p.kode AS item_kode', 'i.nama AS item_nama', 'l.kode AS lokasi_kode', 'l.nama AS lokasi_nama', 'k.npk', 'k.nama AS karyawan_nama')
            ->leftJoin('items AS i', 'i.kode', '=', 'p.kode')
            ->leftJoin('lokasis AS l', 'l.kode', '=', 'p.lokasi')
            ->leftJoin('karyawans AS k', 'k.npk', '=', 'p.npk')
            ->whereRaw('DATE(p.tanggal_transaksi) = "'. date('Y-m-d', strtotime($tanggal)) .'"');

        if (! empty($lokasi)) {
            $query = $query->where('p.lokasi', $lokasi);
        }

        $produksi = $query->paginate(10);

        return view('admin.produksi.index', [
            'tanggal' => $tanggal,
            'lokasi' => $lokasi,
            'produksi' => $produksi,
            'list_lokasi' => $list_lokasi
        ]);
    }

    public function tambah(Request $request){
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'tanggal' => 'required|date',
                'lokasi' => 'required|string',
                'item' => 'required|string',
                'qty' => 'required|numeric'
            ]);

            $tambah = Produksi::create([
                'tanggal_transaksi' => date('Y-m-d H:i:s', strtotime($request->tanggal)),
                'lokasi' => $request->lokasi,
                'kode' => $request->item,
                'qty_actual' => (int) $request->qty,
                'npk' => $request->session()->get('credentials')->npk,
            ]);

            if ($tambah) {
                Helpers::setAlert([
                    'type' => 'success',
                    'message' => 'Tambah data berhasil!'
                ]);

                return redirect()->route('admin.produksi');
            }else{
                Helpers::setAlert([
                    'type' => 'error',
                    'message' => 'Tambah data gagal!'
                ]);
            }
        }

        $lokasi = Lokasi::all();
        $item = Item::all();

        return view('admin.produksi.create', [
            'list_lokasi' => $lokasi,
            'list_item' => $item
        ]);
    }

    public function edit(Request $request, $id){
        $produksi = Produksi::find($id);
        if (! $produksi) {
            Helpers::setAlert([
                'type' => 'error',
                'message' => 'Data tidak sedia!'
            ]);

            return redirect()->route('admin.produksi');
        }

        if ($request->isMethod('post')) {
            $this->validate($request, [
                'tanggal' => 'required|date',
                'lokasi' => 'required|string',
                'item' => 'required|string',
                'qty' => 'required|numeric'
            ]);

            $produksi->tanggal_transaksi = date('Y-m-d H:i:s', strtotime($request->tanggal));
            $produksi->lokasi = $request->lokasi;
            $produksi->kode = $request->item;
            $produksi->qty_actual = (int) $request->qty;
            // $produksi->npk = $request->session()->get('credentials')->npk;

            if ($produksi->update()) {
                Helpers::setAlert([
                    'type' => 'success',
                    'message' => 'Tambah data berhasil!'
                ]);

                return redirect()->route('admin.produksi');
            }else{
                Helpers::setAlert([
                    'type' => 'error',
                    'message' => 'Tambah data gagal!'
                ]);
            }
        }

        $lokasi = Lokasi::get();
        $item = Item::get();

        // dd($item);

        return view('admin.produksi.update', [
            'list_lokasi' => $lokasi,
            'list_item' => $item,
            'produksi' => $produksi
        ]);
    }

    public function hapus(Request $request, $id){
        $output = Helpers::templateOutput();
        $produksi = Produksi::find($id);
        if (! $produksi) {
            $output['alert']['message'] = 'Data tidak tersedia!';
        }

        if ($produksi->delete()) {
            $output['result'] = 1;
            $output['alert']['type'] = 'success';
            $output['alert']['message'] = 'Hapus data berhasil!';
        }else{
            $output['alert']['message'] = 'Hapus data gagal!';
        }

        if ($request->isAjaxRequest()) {
            return response()->json($output);
        }

        Helpers::setAlert([
            'type' => $output['alert']['type'],
            'message' => $output['alert']['message']
        ]);

        return redirect()->route('admin.produksi');
    }

}
