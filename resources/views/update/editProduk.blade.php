@extends('layouts.main')

@section('container')
    <div class="page-inner">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <form method="post" action="/produk/{{ $produks->id }}">
                            @method('put')
                            @csrf
                            <div class="row">
                                <div class="form-group">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input id="nama" type="text" name="nama"
                                        class="form-control @error('nama') is-invalid  @enderror"
                                        value="{{ old('nama', $produks->nama) }}">
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                {{-- </div>
                            <div class="row"> --}}
                                <div class="form-group">
                                    <label for="kode" class="form-label">Kode</label>
                                    <input id="kode" type="text" name="kode"
                                        class="form-control @error('kode') is-invalid  @enderror"
                                        value="{{ old('kode', $produks->kode) }}">
                                    @error('kode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select class="form-select" name="id_kategori">
                                        @foreach ($kategoris as $kategori)
                                            @if (old('id_kategori, $produks->id_kategori') == $kategori->id)
                                                <option value="{{ $kategori->id }}" selected>
                                                    {{ $kategori->nama }}
                                                </option>
                                            @else
                                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                {{-- </div>
                            <div class="row"> --}}
                                <div class="form-group">
                                    <label for="exp_date" class="form-label">exp_date</label>
                                    <input id="exp_date" type="date" name="exp_date"
                                        class="form-control @error('exp_date') is-invalid  @enderror"
                                        value="{{ old('exp_date', $produks->exp_date) }}">
                                    @error('exp_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input id="harga" type="number" name="harga"
                                        class="form-control @error('harga') is-invalid @enderror"
                                        value="{{ old('harga', $produks->harga) }}">
                                    @error('harga')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                {{-- </div>
                            <div class="row"> --}}
                                <div class="form-group">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input id="stok" type="number" name="stok"
                                        value="{{ old('stok', $produks->stok) }}"
                                        class="form-control @error('stok') is-invalid @enderror">
                                    @error('stok')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" id="submit" class="btn btn-success">Perbarui</button>
                            <a href="/kategori"><button type="button" class="btn btn-success">Kembali</button></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
