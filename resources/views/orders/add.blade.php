@extends('layouts.master')

@section('title')
    <title>Transaksi</title>
@endsection

@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Transaksi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}" >Home</a></li>
                            <li class="breadcrumb-item active">Transaksi</li>
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
                                <div class="card-title"></div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <form action="#" @submit.prevent="addToCart" method="post">
{{--                                            @csrf--}}
                                        <div class="form-group">
                                            <label for="product_id">Produk</label>
                                            <select name="product_id" id="product_id"
                                                    v-model="cart.product_id"
                                                    class="form-control" width="100%">
                                                <option value="">Pilih</option>
                                                @foreach($products as $product)
                                                <option value="{{$product->id}}">
                                                    {{$product->code}} - {{$product->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="qty">Qty</label>
                                            <input type="number" name="qty" id="qty"
                                                   v-model="cart.qty" value="1" min="1"
                                                   class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-sm"
                                            :disabled="submitCart">
                                                <i class="fa fa-shopping-cart"></i> @{{ submitCart ? 'Loading ...' : 'Ke Keranjang' }}
                                            </button>
                                        </div>
                                        </form>
                                    </div>

{{--                                    Menampilkan Detail Product--}}
                                    <div class="col-md-5">
                                        <h4>Detail Produk</h4>
                                        <div v-if="product.name">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th>Kode</th>
                                                    <td>:</td>
                                                    <td>@{{ product.code }}</td>
                                                </tr>
                                                <tr>
                                                    <th width="3%">Produk</th>
                                                    <td wid th="2%">:</td>
                                                    <td>@{{ product.name}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Harga</th>
                                                    <td>:</td>
                                                    <td>@{{ product.price | currency }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

{{--                                    Menampilkan Image dari Product--}}
                                    <div class="col-md-3" v-if="product.photo">
                                        <img :src="'uploads/product/' +product.photo"
                                             height="150px"
                                             width="150px"
                                             :alt="product.name">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    @include('orders.cart')
{{--                    Menampilkan List Product Yang ada di keranjang--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-header with-border">--}}
{{--                                <div class="card-title">--}}
{{--                                    <p>Keranjang</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="card-body">--}}
{{--                                <table class="table table-hover">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th>Produk</th>--}}
{{--                                        <th>Harga</th>--}}
{{--                                        <th>Qty</th>--}}
{{--                                        <th>Action</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    --}}{{--Menggunakan Looping VUE JS--}}
{{--                                    <tr v-for="(row, index) in shoppingCart">--}}
{{--                                        <td>@{{ row.name }} (@{{ row.code }})</td>--}}
{{--                                        <td>@{{ row.price | currency }}</td>--}}
{{--                                        <td>@{{ row.qty }}</td>--}}
{{--                                        <td>--}}
{{--                                            --}}{{---Event Onclick untuk menghapus cart--}}
{{--                                            <button @click.prevent="removeCart(index)"--}}
{{--                                                    class="btn btn-danger btn-sm">--}}
{{--                                                <i class="fa fa-trash"></i>--}}
{{--                                            </button>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                                <div class="card-footer text-muted">--}}
{{--                                    <a href="{{route('order.checkout')}}"--}}
{{--                                       class="btn btn-info btn-sm float-right">--}}
{{--                                        Checkout--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </section>
    </div>
@endsection

