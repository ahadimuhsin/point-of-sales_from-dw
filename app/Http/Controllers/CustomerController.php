<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    //
    public function search(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email'
        ])->validate();

        $customer = Customer::where('email', $request->email)->first();

        if($customer){
            return response()->json([
                'status' => 'success',
                'data' => $customer
            ],200);
        }
        return response()->json([
            'status' => 'failed',
            'data' => []
        ]);
    }
}
