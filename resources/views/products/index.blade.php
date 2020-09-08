@extends('layouts.master')

@section('title')
    <title>Manajemen Produk</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Manajemen Produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Produk</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header with-border">
                                <a href="{{route('product.create')}}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i>Tambah
                                </a>
{{--                                <h3 class="card-title">List Produk</h3>--}}
                            </div>
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{session('success')}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nomor</th>
                                                <th>Nama Produk</th>
                                                <th>Stok</th>
                                                <th>Harga</th>
                                                <th>Kategori</th>
                                                <th>Last Update</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($products as $product)
                                            <tr>
                                                <td>@if (!empty($product->photo))
                                                        <img src="{{asset('uploads/product/'.$product->photo)}}"
                                                        alt="{{$product->name}}" width="50px" height="50px">
                                                    @else
                                                        <img src="http://via.placeholder.com/50x50" alt="{{ $product->name }}">
                                                    @endif
                                                </td>
                                                <td>
                                                    <sup class="label label-success">({{$product->code}})</sup>
                                                    <strong>{{ucfirst($product->name)}}</strong>
                                                </td>
                                                <td>{{$product->stock}}</td>
                                                <td>Rp {{number_format($product->price)}}</td>
                                                <td>{{$product->category->name}}</td>
                                                <td>{{$product->updated_at}}</td>
                                                <td>
                                                    <form action="{{route('product.destroy', $product->id)}}" method="post"
                                                          onsubmit="return confirm('Anda yakin hapus kategori <?php echo $product->name; ?> ?');">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{route('product.edit', $product->id)}}" class="btn btn-warning btn-sm">
                                                            <i class="fa fa-edit"></i>Edit
                                                        </a>
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i>Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row float-right">
                                        <div class="col">
                                            {{$products->links()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
