<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use Illuminate\Http\Request;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $product = Product::count();
        $order = Order::count();
        $customer = Customer::count();
        $user = User::count();
        return view('home', compact('product', 'order', 'customer', 'user'));
    }

    //untuk menggenerate data order 7 hari terakhir
    public function getChart()
    {
        //mengambil tanggal 7 hari yang lalu dari hari ini
        $start = Carbon::now()->subWeek()->addDay()->format('Y-m-d'). ' 00:00:01';

        //mengambil tanggal hari ini
        $end = Carbon::now()->format('Y-m-d').' 23:59:59';

        //select data kapan record dibuat dan total pesanan
        $order = Order::select(DB::raw('date(created_at) as order_date'),
            DB::raw('count(*) as total_order'))
            //dengan kondisi antara tangal yang ada di variabel start dan end
        ->whereBetween('created_at', [$start, $end])
            //kelompokkan berdasarkan tanggal
        ->groupBy('created_at')
        ->get()->pluck('total_order', 'order_date')->all();

        //looping tanggal dengan interval seminggu terakhir
        for($i = Carbon::now()->subWeek()->addDay(); $i <= Carbon::now(); $i->addDay()){
            //jika di dalam array ada key berbentuk tanggal
            if(array_key_exists($i->format('Y-m-d'), $order)){
                //total pesanannya dipush dengan key tanggal
                $data[$i->format('Y-m-d')] = $order[$i->format('Y-m-d')];
            }
            else{
                $data[$i->format('Y-m-d')] = 0;
            }
        }
        return response()->json($data);
    }
}
