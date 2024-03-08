@extends('layouts.main')

@section('container')
    <div class="page-inner">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <form method="post" action="/keuangan">
                            @csrf
                            <div class="row">
                                <div class="form-group">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <input id="keterangan" type="text" name="keterangan"
                                        class="form-control @error('keterangan') is-invalid  @enderror"
                                        value="{{ old('keterangan') }}">
                                    @error('keterangan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label>Jenis</label>
                                    <select class="form-select" name="jenis">
                                        <option selected>Pilih Jenis Keuangan</option>
                                        <option value="keluar">Keluar</option>
                                        <option value="masuk">Masuk</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input id="jumlah" type="number" name="jumlah"
                                        class="form-control @error('jumlah') is-invalid @enderror"
                                        value="{{ old('jumlah') }}">
                                    @error('jumlah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" id="submit" class="btn btn-success">Tambah</button>
                            <a href="/keuangan"><button type="button" class="btn btn-success">Kembali</button></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
