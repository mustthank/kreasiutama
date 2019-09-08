<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Tmptransaksi;
use App\Models\Piutang;
use App\Models\Tmppiutang;

class TransaksiController extends Controller
{
    private $jenistransaksi;
    private $saldo;
    private $dp;
    
    public function all()
    {
        $datatransaksi = Transaksi::orderBy('id_transaksi','desc')->where('view','show')->get();
        return response()->json(["datatransaksi" => $datatransaksi]);
        
    }
    public function create(Request $request)
    {
        $nomor = $this->setNoTransaksi();
        $nomorbukti = $this->setNoBukti();
        $this->cekpiutang(Request("id_customer"));
        if ($this->saldo > $request->total) {
            $this->dp = $request->total;
            $this->jenistransaksi = "lunas";
            $sisa = 0;
            $saldo = $this->saldo - $request->total;
        } else {
            $this->dp= $this->saldo;
            $this->jenistransaksi = "bon";
            $sisa = $request->total - $this->saldo;
            $saldo = 0;
        }
        // $sisanya = $request->total - $this->dp;
        Tmptransaksi::create
        ([
            "id_transaksi"=> $nomor,
            "tanggal" =>date("Y-m-d"),
            "quantity" => Request("quantity"),
            "id_customer" => Request("id_customer"),
            "nama_barang" => Request("nama_barang"),
            "harga_satuan" => Request("harga_satuan"),
            "total" => Request("total"),
            "status" => $this->jenistransaksi,
            "DP" => $this->dp,
            "sisa" => $sisa,
            "keterangan" => Request("keterangan"),
            "view" => "show",
            "username" => Request("username")
        ]);
        if ($this->saldo > 0){
            Tmppiutang::create([
                "nomor_bukti" => $nomorbukti,
                "tanggal" =>date("Y-m-d"),
                "id_pembayaran" => $nomor,
                "id_customer" => Request("id_customer"),
                "keterangan"=>"",
                "debet"=>0,
                "kredit"=>$this->dp,
                "saldo"=>$saldo,
                "username"=>Request("username"),
                ]);
        }
       
    }
        
    public function tmp()
    {
        $tmp = Tmptransaksi::where('view','show')->get();
        return response()->json([
            "tmptransaksi" => $tmp
            ]);
            
    }
    public function save(Request $request)
    {
        $username = $request->username;
        $tmpdata = Tmptransaksi::where('username',$username)->get();
        foreach ($tmpdata as $tmpdata) {
            Transaksi::create ([
                "id_transaksi" =>$tmpdata->id_transaksi,
                "tanggal" =>$tmpdata->tanggal,
                "quantity" =>$tmpdata->quantity,
                "id_customer" =>$tmpdata->id_customer,
                "nama_barang" =>$tmpdata->nama_barang,
                "harga_satuan" =>$tmpdata->harga_satuan,
                "total" =>$tmpdata->total,
                "status" =>$tmpdata->status,
                "DP" =>$tmpdata->DP,
                "sisa" =>$tmpdata->sisa,
                "keterangan" =>$tmpdata->keterangan,
                "view" =>$tmpdata->view,
                "username" =>$tmpdata->username,
                ]);
        }
        $tmppi = Tmppiutang::where('username',$username)->get();
        foreach ($tmppi as $data) {
            Piutang::create ([
                "nomor_bukti" =>$data->nomor_bukti,
                "tanggal" =>$data->tanggal,
                "id_pembayaran" =>$data->id_pembayaran,
                "id_customer" =>$data->id_customer,
                "keterangan" =>$data->keterangan,
                "debet" =>$data->debet,
                "kredit" =>$data->kredit,
                "saldo" =>$data->saldo,
                "username" =>$data->username,
                ]);
        };
       Tmptransaksi::where('username',$username)->delete();
       Tmppiutang::where('username',$username)->delete();
            
    }
    public function deletetmp($id)
    {
        $tmp = Tmptransaksi::where('id_transaksi',$id)->delete();
        try {
            $tmpPiutang = Tmppiutang::where('id_pembayaran',$id)->delete();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function setNoTransaksi(){
        $tm = Tmptransaksi::where('tanggal',date('Y-m-d'))->orderBy('id_transaksi','desc')->take(1)->get(['id_transaksi']);
        $tr = Transaksi::where('tanggal',date('Y-m-d'))->orderBy('id_transaksi','desc')->take(1)->get(['id_transaksi']);
        $no = $tr->union($tm);
        if ($no->count()>0) {
            foreach ($no as $key) {
                $nomor = substr($key->id_transaksi,8);
                $nomor = date('ymd').'T'.sprintf('%03s',abs($nomor) +1);
            }
        } else {
            $nomor = date('ymd').'T001';
        }
        return $nomor;
    }
    public function setNoBukti(){
        $piut = Piutang::where('tanggal',date('Y-m-d'))->orderBy('nomor_bukti','desc')->get(['nomor_bukti']);
        $tmppiut = Tmppiutang::where('tanggal',date('Y-m-d'))->orderBy('nomor_bukti','desc')->get(['nomor_bukti']);
        $no = $piut->union($tmppiut)->take(1);
        if ($no->count()>0) {
            foreach ($no as $key) {
                $nomor = substr($key->nomor_bukti,9);
                $nomorbukti = date('ymd').'PU'.sprintf('%03s',abs($nomor) +1);
            }
        } else {
            $nomorbukti = date('ymd').'PU001';
        }
        return $nomorbukti;
    }
    public function cekpiutang($idcust){
        $piutang = Piutang::where('id_customer',$idcust);
        $tmpPiutang = Tmppiutang::where('id_customer',$idcust);
        $union = $piutang->union($tmpPiutang)->OrderBy('nomor_bukti','desc')->take(1)->get();
        if ($union->count()>0) {
            //   $this->jenistransaksi = "ada";
            $this->saldo = $union->sum("Saldo");
            $this->dp=0;
            return true;
        } else {
            //   $this->jenistransaksi = "tdkada";
            $this->saldo =0 ;
            $this->dp = 0;
            return false;
        }
        // return $jenistransaksi;
    }
    public function savePiutang(){
        Tmppiutang::create([
            "nomor_bukti" => $nomorbukti,
            "tanggal" =>date("Y-m-d"),
            "id_pembayaran" => $nomor,
            "id_customer" => Request("id_customer"),
            "keterangan"=>"",
            "debet"=>0,
            "kredit"=>$this->dp,
            "saldo"=>$sisa,
            "username"=>Request("username"),
            ]);
    }
    public function deleteall(Request $request){
        $username = $request->username;
        $del =  Tmptransaksi::where('username',$username)->delete();
        try {
            $del1 =  Tmppiutang::where('username',$username)->delete();
        } catch (\Throwable $th) {
            //throw $th;
        }
     
    }
}