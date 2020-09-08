@extends('layouts.master')

@section('title')
    <title>Role Permission</title>
@endsection

@section('css')
    <style type="text/css">
        .tab-pane{
            height: 150px;
            overflow-y: scroll;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Role Permission</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}" >Home</a></li>
                            <li class="breadcrumb-item active">Role Permission</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        {{--        End Of content-header--}}

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header with-border">
{{--                            <div class="card-title">--}}
                                <h4 class="card-title">Tambah Izin Baru</h4>
                            </div>

                            <div class="card-body">
                                <form action="{{route('users.add_permission')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name"
                                        class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}"
                                        id="name" required>
                                        <p class="text-danger">{{$errors->first('name')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-sm">
                                            Tambah Baru
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header with-border">
                                <div class="card-title">
                                    <h3>Atur Perizinan Role</h3>
                                </div>
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
                            <form action="{{route('users.roles_permission')}}" method="get">
                                <div class="form-group">
                                    <label for="">Roles</label>
                                    <div class="input-group">
                                        <select name="role" class="form-control">
                                            @foreach($roles as $role)
                                                <option value="{{$role}}"
                                                    {{request()->get('role') == $role ? 'selected' : ''}}>
                                                        {{$role}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger">Check!</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                            </div>
{{--                               Jika $permission tidak bernilai kosong--}}
                            <div class="card-body">
                            @if(!empty($permissions))
                                <form action="{{route('users.setRolePermission', request()->get('role'))}}" method="post">
                                        @csrf
                                        @method('put')
                                    <div class="form-group">
                                        <div class="nav-tabs-custom">
                                            <ul class="nav nav-tabs">
                                                <li class="active">
                                                    <a href="#tab_1" data-toggle="tab">Permissions</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" style="height: 150px;
                                                overflow-y: scroll;">
                                                    @php $no=1; @endphp
                                                    @foreach($permissions as $key=>$row)
                                                        <input type="checkbox" name="permission[]"
                                                            class="minimal-red"
                                                            value="{{$row}}"
                                                            {{--Jika Permission sudah diset, set keadaan jadi checked--}}
                                                            {{in_array($row, $hasPermission) ? 'checked': ''}}>
                                                            {{$row}}
                                                            <br>
                                                        @if ($no++%4 == 0)
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pull-right">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fa fa-send"></i>Set Permission
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
