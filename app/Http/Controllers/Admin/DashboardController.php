<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Components\Helpers;
use App\Components\Report;

class DashboardController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('admin.dashboard.index');
    }

    public function chartProduksi(Request $request)
    {
        $output = Helpers::templateOutput();

        $filter_date = [
            'valid_from' => date('Y-m-d', strtotime('now' . ' -30 days')),
            'valid_until' => date('Y-m-d'),
        ];
        $output['result'] = 1;
        $output['alert']['type'] = 'success';
        $output['alert']['message'] = 'Get data chart produksi is success!';
        $output['model'] = [
            'title' => 'Transaction Produksi By Achivement',
            'subtitle' => 'Periode ' . $filter_date['valid_from'] . ' - ' . $filter_date['valid_until'],
            'periode' => $filter_date['valid_from'] . ' - ' . $filter_date['valid_until'],
            'data' => []
        ];

        $data = [];
        $achivement = DB::table('achivements AS a')->get();
        foreach ($achivement as $key => $item) {
            $query = DB::table('produksis AS p')
                        ->rightJoinSub(Report::_stringQueryDateSequence($filter_date['valid_from'], $filter_date['valid_until']), "t", function($join) use ($filter_date, $item){
                            $join->where("t.Date", "=", DB::RAW("DATE(p.tanggal_transaksi)"));
                            $join->whereRaw('TIME(p.tanggal_transaksi) BETWEEN "'. $item->time_from .'" AND "'. $item->time_to .'"');
                        })
                        ->selectRaw('t.Date, SUM(COALESCE(p.qty_actual, 0)) AS qty')
                        ->groupBy('t.Date');

            $temp_data = $query->get()->toArray();
            $data[] = [
                'kode' => $item->kode,
                'data' => $temp_data
            ];
        }


        // $array_date = DB::select(Report::_stringQueryDateSequence($filter_date['valid_from'], $filter_date['valid_until']));
        //
        // // dd($array_date);
        //
        // foreach ($array_date as $key => $value) {
        //     $value = $value->DATE;
        //     $query = DB::table('achivements AS a')
        //                 ->leftJoin('produksis AS p', function($join) use ($value){
        //                     $join->whereRaw('TIME(p.tanggal_transaksi) BETWEEN a.time_from AND a.time_to AND DATE(p.tanggal_transaksi) = "'. $value .'"');
        //                 })
        //                 ->selectRaw('a.kode, a.time_from, a.time_to, SUM(COALESCE(p.qty_actual, 0)) AS qty')
        //                 ->groupBy('a.kode', 'a.time_from', 'a.time_to');
        //
        //     $data[$value] = $query->get()->toArray();
        // }

        // dd($data);

        // // store to output
        $output['model']['data'] = $data;

        return response()->json($output);
    }

    public function chartBestLokasi(Request $request)
    {
        $output = Helpers::templateOutput();

        $filter_date = [
            'valid_from' => date('Y-m-d', strtotime('now' . ' -30 days')),
            'valid_until' => date('Y-m-d'),
        ];
        $output['result'] = 1;
        $output['alert']['type'] = 'success';
        $output['alert']['message'] = 'Get data chart best lokasi is success!';
        $output['model'] = [
            'title' => 'Best Lokasi Based on Transaksi Produksi',
            'subtitle' => 'Periode ' . $filter_date['valid_from'] . ' - ' . $filter_date['valid_until'],
            'periode' => $filter_date['valid_from'] . ' - ' . $filter_date['valid_until'],
            'data' => []
        ];

        $query = DB::table("lokasis AS l")
                    ->select('l.kode', 'l.nama', DB::RAW('COALESCE(t.total_transaksi, 0) AS total_transaksi'))
                    ->leftJoinSub(DB::table('produksis AS p')
                        ->selectRaw('p.lokasi, COUNT(*) AS total_transaksi')
                        ->whereRaw("DATE(p.tanggal_transaksi) BETWEEN '". $filter_date['valid_from'] ."' AND '". $filter_date['valid_until'] ."'")
                        ->groupBy('p.lokasi'), 't', function($join){
                            $join->on('t.lokasi', '=', 'l.kode');
                        });

        // dd($query->toSql());

        $data = $query->get()->toArray();

        // store to output
        $output['model']['data'] = $data;

        return response()->json($output);
    }

    public function chartBestItem(Request $request)
    {
        $output = Helpers::templateOutput();

        $filter_date = [
            'valid_from' => date('Y-m-d', strtotime('now' . ' -30 days')),
            'valid_until' => date('Y-m-d'),
        ];
        $output['result'] = 1;
        $output['alert']['type'] = 'success';
        $output['alert']['message'] = 'Get data chart best item is success!';
        $output['model'] = [
            'title' => 'Best Item Based on Transaksi Produksi',
            'subtitle' => 'Periode ' . $filter_date['valid_from'] . ' - ' . $filter_date['valid_until'],
            'periode' => $filter_date['valid_from'] . ' - ' . $filter_date['valid_until'],
            'data' => []
        ];

        $query = DB::table("items AS i")
                    ->select('i.kode', 'i.nama', DB::RAW('COALESCE(t.total_transaksi, 0) AS total_transaksi'))
                    ->leftJoinSub(DB::table('produksis AS p')
                        ->selectRaw('p.kode, COUNT(*) AS total_transaksi')
                        ->whereRaw("DATE(p.tanggal_transaksi) BETWEEN '". $filter_date['valid_from'] ."' AND '". $filter_date['valid_until'] ."'")
                        ->groupBy('p.kode'), 't', function($join){
                        $join->on('t.kode', '=', 'i.kode');
                    });

        $data = $query->get()->toArray();

        // store to output
        $output['model']['data'] = $data;

        return response()->json($output);
    }

}
