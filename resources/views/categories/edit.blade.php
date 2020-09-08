@extends('layouts.master')

@section('title')
    <title>Edit Kategori</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Edit Kategori</h1>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('category.index')}}">Kategori</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header with-border">
                                <h3 class="card-title">Edit Data Kategori</h3>
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
                                <form role="form" action="{{route('category.update', $category->id_category)}}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="name">Nama Kategori</label>
                                        <input type="text" name="name" value="{{$category->name}}"
                                               class="form-control {{$errors->has('name') ? 'is-invalid': ''}}"
                                               id="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea name="description" id="description" cols="5" rows="5"
                                                  class="form-control {{$errors->has('description') ? 'is-invalid' : ''}}">{{$category->description}}</textarea>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-info">Update</button>
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
