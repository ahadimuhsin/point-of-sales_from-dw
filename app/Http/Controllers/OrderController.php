<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Order_detail;
use Illuminate\Support\Facades\Cookie;
use App\Customer;
use App\Exports\OrderInvoice;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //mengambil data customer
        $customers = Customer::orderBy('name', 'asc')->get();

        //mengambil data user yang memiliki role kasir
        $users = User::role('kasir')->orderBy('name', 'asc')->get();

        //mengambil data transaksi
        $orders = Order::orderBy('created_at', 'desc')->with('order_detail', 'customer');

        //jika pelanggan dipilih pada combo box
        if(!empty($request->customer_id)){
            //tambahkan where condition
            $orders = $orders->where('customer_id', $request->customer_id);
        }

        //jika user/kasir dipilih pada combo box
        if(!empty($request->user_id)){
            //tambahkan where condition
            $orders = $orders->where('user_id', $request->user_id);
        }

        //Jika start date & end date terisi
        if(!empty($request->start_date) && !empty($request->end_date))
        {
            //validasi format datenya
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date'
            ]);

            //Ubah format tanggal menjadi Y-m-d H:i:s
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d'). '00:00:01';
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d'). '23:59:59';

            //tambahkan wherebetween condition untuk mengambil data dengan range
            $orders = $orders->whereBetween('created_at', [$start_date, $end_date])->get();
        }
        else{
            //jika start date dan end date kosong
            $orders = $orders->take(10)->skip(0)->get();
        }

        //menampilkan view
        return view('orders.index', [
            'orders' => $orders,
            'sold' => $this->countItem($orders),
            'total' => $this->countTotal($orders),
            'total_customer' => $this->countCustomer($orders),
            'customers' => $customers,
            'users' => $users
        ]);
    }

    /*
     * Fungsi untuk menghitung jumlah Customer
     */
    public function countCustomer($orders)
    {
        //definisikan array kosong
        $customer = [];
        //jika ada data yang ditampilkan
        if($orders->count() > 0){
            //looping untuk menyimpmpan email ke dalam array
            foreach ($orders as $row){
                $customer[] = $row->customer->email;
            }
        }

        //menghitung total yang ada di dalam array
        //dimana data yang duplikat akan dihapus mneggunakan array_unique
        return count(array_unique($customer));
    }

    /*
     * Menghitung Total Orders
     */
    public function countTotal($orders)
    {
        //default value = 0
        $total = 0;

        //jika ada data
        if($orders->count() > 0){
            //mengambil value dari total, pluck akan mengubahnya menjadi array
            $sub_total = $orders->pluck('total')->all();
            //kemudian jumlahkan data yang ada di dalam array
            $total = array_sum($sub_total);
        }
        return $total;
    }

    /*
     * Menghitung total Item
     */
    public function countItem($order)
    {
        //defaul data 0
        $data = 0;
        //jika data ada
        if($order->count() > 0){
            //looping
            foreach ($order as $row){
                //ambil qty
                $qty = $row->order_detail->pluck('qty')->all();
                //jumlahkan qty
                $val = array_sum($qty);
                $data += $val;
            }
        }
        return $data;
    }

    /*
     * Fungsi untuk mendownload invoice sebagai PDF
     */
    public function invoicePdf($invoice)
    {
        //ambil data transaksi berdasakan invoice
        $order = Order::where('invoice', $invoice)
            ->with('customer', 'order_detail', 'order_detail.product')->first();
        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])
            ->loadView('orders.report.invoice', compact('order'));

        return $pdf->stream('invoice-'.$invoice.'.pdf');
    }

    /*
     * Fungsi untuk mendownload invoice sebagai excel
     */
    public function invoiceExcel($invoice)
    {
        return (new OrderInvoice($invoice))->download('invoice-'.$invoice.'.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /*
     * Fungsi untuk menambah Order
     */
    public function addOrder()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
//        dd($product);
        return view('orders.add', compact('products'));
    }

    /*
     * Fungsi untuk generate Invoice
     */
    public function generateInvoice()
    {
        $order = Order::orderBy('created_at', 'desc');

        //jika sudah ada records
        if($order->count() > 0){
            //ambil data pertama yang sudah disort desc
            $order = $order->first();
            //explode invoice untuk dapat angkanya
            $explode = explode('-', $order->invoice);
            $count = $explode[1] + 1;
            //angka dari hasil explode +1
            return 'INV-' .$count;
        }
        //kalo belum ada record
        return 'INV-1';
    }

    /*
     * Fungsi untuk menyimpan Order
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:100',
            'address' => 'required',
            'phone' => 'required|numeric',
        ]);

        //mengambil list cart dari cookie
        $cart = json_decode($request->cookie('cart'), true);

        //manipulasi array untuk menciptakan key baru, hasil dari perkalian price * qty
        $result = collect($cart)->map(function ($value){
            return [
                'code' => $value['code'],
                'name' => $value['name'],
                'qty' => $value['qty'],
                'price' => $value['price'],
                'result' => $value['price'] * $value['qty']
            ];
        })->all();
//        echo ($result);
//        var_dump($result);
        //database transaction
        DB::beginTransaction();
        try{
            //menyimpan data ke table customers
            $customer = Customer::firstOrCreate([
                'email' => $request->email
                ],[
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone
            ]);

            //menyimpan data ke tabel Orders
            $order = Order::create([
                'invoice' => $this->generateInvoice(),
                'customer_id' => $customer->id,
                'user_id' => Auth::user()->id,
                'total' => array_sum(array_column($result, 'result'))
            ]);

            //looping cart untuk disimpan ke order_details
            foreach ($result as $key => $row){
                $order->order_detail()->create([
                    'product_id' => $key,
//                    'order_id' => $order->id,
                    'qty' => $row['qty'],
                    'price' => $row['price']
                ]);
            }
            //apabila tidak terjadi error, penyimpanan diverifikasi
            DB::commit();

            //return status dan message, dan menghapus cookie
            return response()->json([
                'status' => 'success',
                'message' => $order->invoice,
            ], 200)->cookie(Cookie::forget('cart'));
        } catch (\Exception $e)
        {
            //jika ada error, data akan dirollback sehingga tidak ada data yang tersimpan
            DB::rollBack();

            //kirim pesan gagal
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /*
     * Fungsi mengambil data product
     */
    public function getProduct($id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product, 200);
    }

    /*
     * Fungsi untuk memasukkan data ke dalam cart
     */

    public function addToCart(Request $request){
        //validasi data yang diterima
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer',
        ]);

        $product = Product::findOrFail($request->product_id);
        //mengambil cookie cart
        $getCart = json_decode($request->cookie('cart'), true);
//        echo ($getCart);

        //jika datanya ada
        if($getCart){
            //jika keynya ada berdasarkan product_id
            if(array_key_exists($request->product_id, $getCart)){
                //jumlahkan qty barangnya
                $getCart[$request->product_id]['qty'] += $request->qty;
                //dikirim kembali untuk disimpan ke cookie
                return response()->json($getCart, 200)
                    ->cookie('cart', json_encode($getCart), 120);
            }
        }

        //jika cart kosong, tambahka ke cart baru
        $getCart[$request->product_id] = [
            'code' => $product->code,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => $request->qty
        ];

        //kirim responsenya kemudian simpan ke cookie
        return response()->json($getCart, 200)
            ->cookie('cart', json_encode($getCart), 120);
    }

    //method untuk mendapatkan cart
    public function getCart()
    {
        $cart = json_decode(request()->cookie('cart'), true);

        return response()->json($cart, 200);
    }

    //untuk menampilkan form checkout
    public function checkout()
    {
        return view('orders.checkout');
    }

    //method untuk menghapus cart
    public function removeCart($id)
    {
        $cart = json_decode(\request()->cookie('cart'), true);

        //menghapus cart berdasarkan id
        unset($cart[$id]);
        //cart diperbarui
        return response()->json($cart, 200)
            ->cookie('cart', json_encode($cart), 120);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
