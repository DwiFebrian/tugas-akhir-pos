@extends('layouts.main')

@section('container')
    <div class="page-inner">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <form method="post" action="/user" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input id="nama" type="text" name="nama"
                                        class="form-control @error('nama') is-invalid  @enderror"
                                        value="{{ old('nama') }}">
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="text" name="email"
                                        class="form-control @error('email') is-invalid  @enderror"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input id="password" type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-select" name="role">
                                        <option selected>Pilih Role Pengguna</option>
                                        <option value="admin">admin</option>
                                        <option value="kasir">kasir</option>
                                        <option value="gudang">Staf Gudang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input class="form-control @error('foto') is-invalid @enderror" type="file"
                                        id="foto" name="foto">
                                    @error('foto')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" id="submit" class="btn btn-success">Tambah</button>
                            <a href="/user"><button type="button" class="btn btn-success">Kembali</button></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
