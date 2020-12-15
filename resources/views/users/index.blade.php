@extends('layouts.master')

@section('title')
    <title>Manajemen User</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Manajemen User</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}" >Home</a></li>
                            <li class="breadcrumb-item active">User</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
{{--        End Of content-header--}}

{{--        Konten Utama--}}
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header with-border">
                                <div class="card-title">
                                    Daftar User
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
                                            <td>Nama</td>
                                            <td>Email</td>
                                            <td>Role</td>
                                            <td>Status</td>
                                            <td>Aksi</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $no=1; @endphp
                                        @forelse($users as $user)
                                            <tr>
                                                <td>{{$no++}}</td>
                                                <td>{{$user->name}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>
                                                    @foreach($user->getRoleNames() as $role)
                                                        <label for="" class="badge badge-info">{{$role}}</label>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if($user->status)
                                                        <label class="badge badge-success">Aktif</label>
                                                    @else
                                                        <label for="" class="badge badge-danger">Tidak Aktif</label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{route('users.destroy', $user->id)}}" method="post"
                                                    onsubmit="return confirm('Yakin mau hapus user ini?')">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{route('users.roles', $user->id)}}" class="btn btn-info btn-sm">
                                                            <i class="fa fa-user-secret"></i>
                                                        </a>
                                                        <a href="{{route('users.edit', $user->id)}}" class="btn btn-warning btn-sm">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-danger btn-sm" type="submit" value="HAPUS"><i class="fa fa-trash"></i> </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Data Kosong</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="10">
                                                    {{ $users->appends(Request::all())->links() }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
