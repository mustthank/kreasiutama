<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use Faker\Provider\Image;

// use App\Http\Requests\CreateCustomerRequest;

class CustomersController extends Controller
{
    public function all()
    {
        $customers = Customer::orderBy('id_customer','desc')->get();
        return response()->json([
            "customers" => $customers
        ]);
    }
    public function get($id)
    {
        $customer = Customer::whereId($id)->first();
        return response()->json([
            "customer" => $customer
        ], 200);
    }
    public function create(Request $request)
    {
        $idcustomer = $this->setidcustomer();
        $nama_pelanggan = $request->nama_pelanggan;
        $alamat = $request->alamat;
        $telpon = $request->telpon;
        $email  = $request->email;
        $photo = $request->photo;
        $name = '';
        if ($photo !== '') {
            $name= time().'.' . explode('/',explode(':',substr($photo,0,strpos($photo,';')))[1])[1];
           \Image::make($photo)->save(public_path('images/profile/').$name);
        }
        Customer::create([
            "id_customer" => $idcustomer,
            "nama_pelanggan" => $nama_pelanggan,
            "alamat" =>$alamat,
            "telp" => $telpon,
            "email" => $email,
            "photo" => $name
        ]);
        
        // return response()->json([
        //     "customer" => $customer
        // ]);
    }
    public function setidcustomer()
    {
        $customer = Customer::all();
        $idcustomer = "C-".sprintf($customer->count()+1);
        return $idcustomer;
    }
    public function delete($id)
    {
        $tmp = Customer::where('id_customer',$id)->delete();
    }
}