@extends('layouts.auth')

@section('title')
    <title>Login</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in</p>

            <form method="POST" action="{{route('login')}}">
                @csrf
                @if(session('error'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{session('error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="input-group has-feedback mb-3">
                    <input type="email" required name="email"
                    class="form-control {{$errors->has('email') ? 'is-invalid' : ''}}"
                    placeholder="{{__('Email Address')}}"
                    value="{{old('email')}}" style="border-right-color: white">
                    <div class="input-group-append">
                        <div class="input-group-text" style="background-color: white">
                            <span class="fa fa-envelope form-control-feedback">{{$errors->first('email')}}</span>
                        </div>
                    </div>
                </div>

                <div class="input-group has-feedback mb-3">
                    <input type="password" required name="password"
                           class="form-control {{$errors->has('password') ? 'is-invalid' : ''}}"
                           placeholder="{{__('Password')}}"
                           value="{{old('password')}}" style="border-right-color: white">
                    <div class="input-group-append">
                        <div class="input-group-text" style="background-color: white">
                            <span class="fa fa-lock form-control-feedback">{{$errors->first('password')}}</span>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember {{old('remember') ? 'checked' : ''}}">
                                {{__('Remember Me')}}
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                </div>
            </form>

            <div class="social-auth-links text-center mb-3">
                <p>- OR -</p>
                <a href="#" class="btn btn-block btn-primary">
                    <i class="fa fa-facebook mr-2"></i> Sign in using Facebook
                </a>
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fa fa-google-plus mr-2"></i> Sign in using Google+
                </a>
            </div>
            ​
            <p class="mb-1">
                <a href="#">I forgot my password</a>
            </p>
            <p class="mb-0">
                <a href="#" class="text-center">Register a new membership</a>
            </p>
        </div>
    </div>
@endsection
