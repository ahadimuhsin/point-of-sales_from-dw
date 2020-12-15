@extends('layouts.master')

@section('title')
    <title>Manajemen Kategori</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Manajemen Kategori</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Kategori</li>
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
                                <h3 class="card-title">Tambah Data Kategori</h3>
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
                                <form role="form" action="{{route('category.store')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Kategori</label>
                                        <input type="text" name="name" class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}"
                                               id="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea name="description" id="description" cols="5" rows="5" class="form-control {{$errors->has('description') ? 'is-invalid' : ''}}"></textarea>
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
                                <h3 class="card-title">List Kategori</h3>
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
                                            <td>Nomor</td>
                                            <td>Kategori</td>
                                            <td>Deskripsi</td>
                                            <td>Aksi</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $no = 1; @endphp
                                        @forelse($categories as $category)
                                            <tr>
                                                <td>{{$no++}}</td>
                                                <td>{{$category->name}}</td>
                                                <td>{{$category->description}}</td>
                                                <td>
                                                    <form action="{{route('category.destroy', $category->id)}}" method="post"
                                                          onsubmit="return confirm('Anda yakin hapus kategori <?php echo($category->name); ?> ?');">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{route('category.edit', $category->id)}}" class="btn btn-warning btn-sm">
                                                            <i class="fa fa-edit"></i>Edit
                                                        </a>
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" onclick="deleteConfirm();"></i>Hapus </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="10">
                                                    {{ $categories->appends(Request::all())->links() }}
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

