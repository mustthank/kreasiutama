<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use App\Models\Tmppembayaran;
use App\Models\Transaksi;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function tmppembayaran()
    {
        // $pembayaran = Tmppembayaran::all();
        // $transaksi = Transaksi::where('id_transaksi', $pembayaran['id_transaksi'])->get();
        // $tmp = $pembayaran->join($transaksi);
        // $tmp = Tmppembayaran::with('transaksi')->get();
        $tmp = DB::table('tmp_pembayaran')
        ->join('detail_transaksi','tmp_pembayaran.id_transaksi','=','detail_transaksi.id_transaksi')->get();
        return response()->json([
            "tmppembayaran" => $tmp
            ]);
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id_customer = $request->id_customer['id_customer'];
        $total_bon =  $request->total_bon;
        $total_bayar = $request->total_pembayaran;
        $sisa = 0;
        $username = $request->username;
        $nomor = $this->setNoPembayaran();
        $transaksi = Transaksi::where('id_customer',$id_customer)->where('sisa','>','0')->orderBy('id_transaksi','asc') ->get();
        foreach ($transaksi as $data) {
           if ($total_bayar > 0) {
              if ($data->sisa < $total_bayar) {
                Tmppembayaran::create
                ([
                    "id_transaksi"=> $data->id_transaksi,
                    "id_pembayaran" => $nomor,
                    "tanggal" =>date("Y-m-d"),
                    "bayar" => $data->total,
                    "username" => $username
                ]);
                $total_bayar = $total_bayar - $data->total;
              } else {
                Tmppembayaran::create
                ([
                    "id_transaksi"=> $data->id_transaksi,
                    "id_pembayaran" => $nomor,
                    "tanggal" =>date("Y-m-d"),
                    "bayar" => $total_bayar,
                    "username" => $username
                ]);
                $total_bayar = 0;
              }
              
           } else {
               # code...
           }
           
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $username = $request->username;
        $id_customer = $request->id_customer['id_customer'];
         $id_pembayaran = "";
        $tmpdata = Tmppembayaran::where('username',$username)->get();
        foreach ($tmpdata as $data) {
            $transaksi = Transaksi::where('id_transaksi', $data->id_transaksi)->first();
            if ($transaksi->total == $data->bayar) {
                $status = 'lunas';
                $sisa = 0;
                $DP = $transaksi->total;
            } else {
                $status = 'bon';
                $sisa = $transaksi->total - $data->bayar;
                $DP = $transaksi->DP + $data->bayar;
            }
            Transaksi::where('id_transaksi', $data->id_transaksi)->update ([
                "status" =>$status,
                "DP" =>$DP,
                "sisa" =>$sisa,
                ]);
                $id_pembayaran = $data->id_pembayaran;
        }
        Pembayaran::create([
            'id_pembayaran' => $id_pembayaran,
            "tanggal" =>date("Y-m-d"),
            "id_customer" => $id_customer,
            "total_pembayaran" => $request->total_pembayaran,
            "detail_Pembayaran" => $request->detail_pembayaran
        ]);
       Tmppembayaran::where('username',$username)->delete();
    //    Tmppiutang::where('username',$username)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function deleteall(Request $request){
        $username = $request->username;
        // $tmppemb = Tmppembayaran::where('username',$username);
        // try {
            $del1 =  Tmppembayaran::where('username',$username)->delete();
        // } catch (\Throwable $th) {
            //throw $th;
        // }
     
    }
    public function setNoPembayaran(){
        // $tm = Tmptransaksi::where('tanggal',date('Y-m-d'))->orderBy('id_transaksi','desc')->take(1)->get(['id_transaksi']);
        $no = Pembayaran::where('tanggal',date('Y-m-d'))->orderBy('id_pembayaran','desc')->take(1)->get(['id_pembayaran']);
        // $no = $tr->union($tm);
        if ($no->count()>0) {
            foreach ($no as $key) {
                $nomor = substr($key->id_pembayaran,7);
                $nomor = date('ymd').sprintf('%03s',abs($nomor) +1);
            }
        } else {
            $nomor = date('ymd').'001';
        }
        return $nomor;
    }
}
