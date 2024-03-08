@extends('layouts.main')

@section('container')
    <div class="page-inner">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <form method="post" action="/kategori">
                            @csrf
                            <div class="row">
                                <div class="form-group">
                                    <label for="nama" class="form-label">Kategori</label>
                                    <input id="nama" type="text" name="nama"
                                        class="form-control @error('nama') is-invalid  @enderror"
                                        value="{{ old('nama') }}">
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" id="submit" class="btn btn-success">Tambah</button>
                            <a href="/kategori"><button type="button" class="btn btn-success">Kembali</button></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
