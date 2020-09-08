@extends('layouts.master')

@section('title')
    <title>Set Role</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Set Role</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}" >Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">User</a> </li>
                            <li class="breadcrumb-item active">Set Role</li>
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
                        <form action="{{route('users.set_role', $user->id)}}"
                              method="post">
                            @csrf
                            @method('put')
                            <div class="card">
                                <div class="card-header with-border">
                                    <div class="card-title">
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
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <td>:</td>
                                            <td>{{$user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>:</td>
                                            <td><a href="mailto:{{$user->email}}">{{$user->email}}</a> </td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td>:</td>
                                            <td>
                                                @foreach($roles as $role)
                                                    <input type="radio" name="role"
                                                    {{$user->hasRole($role) ? 'checked' : ''}}
                                                    value="{{$role}}">{{$role}} <br>
                                                @endforeach
                                            </td>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-sm float-right">
                                        Set Role
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
