@extends('layouts.master')

@section('title')
    <title>Manajemen Role</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Manajemen Role</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> </li>
                            <li class="breadcrumb-item active">Role</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header with-border">
                                <div class="card-title">
                                    Tambah Role
                                </div>
                            </div>
                            @if(session('error'))
                                <div class="alert alert-error alert-dismissible fade show" role="alert">
                                    {{session('error')}}
                                    <button type="button" class="close"
                                            data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="card-body">
                                <form role="form" action="{{route('role.store')}}"
                                      method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Role</label>
                                        <input type="text" name="name"
                                               class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}"
                                        id="name" required>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header with-border">
                                <div class="card-title">
                                    Daftar Role
                                </div>
                            </div>
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{session('success')}}
                                    <button type="button" class="close"
                                            data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Role</td>
                                            <td>Guard</td>
                                            <td>Created At</td>
                                            <td>Aksi</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $no = 1; @endphp
                                        @forelse($role as $row)
                                            <tr>
                                                <td>{{$no++}}</td>
                                                <td>{{$row->name}}</td>
                                                <td>{{$row->guard_name}}</td>
                                                <td>{{$row->created_at}}</td>
                                                <td>
                                                    <form action="{{route('role.destroy', $row->id)}}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-danger btn-sm" value="HAPUS"><i class="fa fa-trash"></i> </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Data Kosong</td>
                                        </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="float-right">
                                    {{$role->appends(Request::all())->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
