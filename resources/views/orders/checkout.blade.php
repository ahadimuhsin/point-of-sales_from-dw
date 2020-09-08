@extends('layouts.master')

@section('title')
    <title>Checkout</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Checkout</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}" >Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('order.transaksi')}}">Transaksi</a> </li>
                            <li class="breadcrumb-item active">Checkout</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content" id="dw">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header with-border">
                                <div class="card-title">
                                    <p>Data Pelanggan</p>
                                </div>
                            </div>

                            <div class="card-body">
                                <div v-if="message" class="alert alert-succes">
                                    Transaksi telah disimpan, Invoice: <strong>#@{{ message }}</strong>
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" name="email"
                                    v-model="customer.email"
                                    class="form-control"
                                    @keyup.enter.prevent="searchCustomer"
                                    required>
                                    <p>Tekan enter untuk mengecek email</p>
                                </div>


                                {{--Jika formCustomer bernilai true, maka form akan ditampilkan--}}
                                <div v-if="formCustomer">
                                    <div class="form-group">
                                        <label for="">Nama Pelanggan</label>
                                        <input type="text" name="name" v-model="customer.name"
                                        :disabled="resultStatus" class="form-control {{$errors->first('name') ? 'is-invalid' : ''}}">
                                        <div class="invalid-feedback">
                                            {{$errors->first('name')}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Alamat</label>
                                        <textarea name="address" class="form-control {{$errors->first('address') ? 'is-invalid' : ''}}"
                                                  :disabled="resultStatus" v-model="customer.address"
                                                  cols="5" rows="5" required></textarea>
                                        <div class="invalid-feedback">
                                            {{$errors->first('name')}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">No Telp</label>
                                        <input type="text" name="phone"
                                        v-model="customer.phone"
                                        :disabled="resultStatus"
                                        class="form-control {{$errors->first('phone') ? 'is-invalid' : ''}}" required>
                                        <div class="invalid-feedback">
                                            {{$errors->first('name')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-muted">
                                    {{--Jika value dari error message ada, maka alert danger akan ditampilkan--}}
                                    <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
                                        @{{ errorMessage }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {{--Jika tombol ditekan, akan memanggil method sendOrder--}}
                                    <button class="btn btn-primary btn-sm float-right"
                                    :disabled="submitForm"
                                    @click.prevent="sendOrder">
                                        @{{ submitForm ? 'Loading...' : 'Order Now' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('orders.cart')
                </div>
            </div>
        </section>
    </div>
@endsection
