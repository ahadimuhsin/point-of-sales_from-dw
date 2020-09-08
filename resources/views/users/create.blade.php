@extends('layouts.master')

@section('title')
    <title>Tambah User Baru</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Tambah User</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}" >Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Manajemen User</a> </li>
                            <li class="breadcrumb-item active">Tambah User</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        {{--        End Of content-header--}}

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header with-border">
                                <div class="card-title">
                                    Tambah User
                                </div>
                            </div>
                            @if(session('error'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{session('error')}}
                                    <button type="button" class="close"
                                            data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="card-body">
                                <form action="{{route('users.store')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" name="name"
                                               class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}"
                                               id="name" required>
                                        <p class="text-danger">{{$errors->first('name')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email"
                                               class="form-control {{$errors->has('email') ? 'is-invalid' : ''}}"
                                               id="email" required>
                                        <p class="text-danger">{{$errors->first('email')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password"
                                               class="form-control {{$errors->has('password') ? 'is-invalid' : ''}}"
                                               id="password" required>
                                        <p class="text-danger">{{$errors->first('password')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select name="role"
                                                class="form-control {{$errors->has('role') ? 'is-invalid': ''}}"
                                        required>
                                            <option value="">Pilih Role</option>
                                            @foreach ($role as $row)
                                                <option value="{{$row->name}}">{{$row->name}}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger">{{$errors->first('role')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fa fa-send"></i>Simpan
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
