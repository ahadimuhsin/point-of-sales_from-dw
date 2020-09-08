@extends('layouts.master')

@section('title')
    <title>Edit Data Produk</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Edit Produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('product.index')}}">Produk</a></li>
                            <li class="breadcrumb-item active">Edit </li>
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
                                <h3 class="card-title">Edit Data Produk</h3>
                            </div>
                            @if(session('error'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    {{session('error')}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="card-body">
                                <form role="form" action="{{route('product.update', $product->id_product)}}"
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                     <div class="form-group">
                                         <label for="">Kode Produk</label>
                                         <input type="text" name="code" required maxlength="20"
                                         readonly value="{{$product->code}}"
                                         class="form-control {{$errors->has('code') ? 'is-invalid' : ''}}">
                                        <p class="text-danger">{{$errors->first('code')}}</p>
                                     </div>

                                    <div class="form-group">
                                        <label for="">Nama Produk</label>
                                        <input type="text" name="name" required
                                        value="{{$product->name}}"
                                        class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}">
                                        <p class="text-danger">{{$errors->first('name')}}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Deskripsi</label>
                                        <textarea type="text" name="description" id="description" cols="5" rows="5"
                                                  class="form-control {{$errors->has('description') ? 'is-invalid': ''}}">{{$product->description}}</textarea>
                                        <p class="text-danger">{{$errors->first('description')}}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Stok</label>
                                        <input type="number" name="stock"
                                               class="form-control {{$errors->has('stock') ? 'is-invalid': ''}}" required
                                                value="{{$product->stock}}">
                                        <p class="text-danger">{{$errors->first('stock')}}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Harga</label>
                                        <input type="number" name="price" value="{{$product->price}}"
                                               class="form-control {{$errors->has('price') ? 'is-invalid': ''}}" required>
                                        <p class="text-danger">{{$errors->first('price')}}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Kategori</label>
                                        <select name="category_id" id="category_id" required
                                                class="form-control {{$errors->has('category_id') ? 'is-invalid' : ''}}">
                                            <option value="">Pilih</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}"
                                                {{$category->id == $product->category_id ? 'selected' : ''}}>
                                                    {{ucfirst($category->name)}}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger">{{$errors->first('category_id')}}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Photo</label>
                                        <input type="file" name="photo"
                                               class="form-control">
                                        <p class="text-danger">{{$errors->first('photo')}}</p>
                                        @if($product->photo)
                                            <hr>
                                            <img src="{{asset('uploads/product/'.$product->photo)}}"
                                            alt="{{$product->name}}"
                                            width="150px" height="150px">
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary btn-md">
                                            <i class="fa fa-send-o"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
