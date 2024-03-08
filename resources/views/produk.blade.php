@extends('layouts.main')

@section('container')
    <div class="page-inner">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Produk</h4>
                        {{-- <a href="/produk/createProduk" class="btn btn-round ml-auto mb-3"><button
                                    style="border:none; background:transparent">
                                    <i class="fa fa-plus"></i>
                                    Tambah
                                </button></a> --}}
                        <a href="/produk/createProduk" class="btn btn-round ml-auto mb-3 btn-primary"><i
                                class="fa fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>ID Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Kadaluwarsa</th>
                                    <th>QR Code</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $produk)
                                    <tr class="text-center">
                                        <td>{{ $produk->nama }}</td>
                                        <td>{{ $produk->kode }}</td>
                                        <td>{{ $produk->id_kategori }}</td>
                                        <td>{{ $produk->harga }}</td>
                                        <td>{{ $produk->stok }}</td>
                                        <td>{{ $produk->exp_date }}</td>
                                        <td>
                                            <div class="visible-print text-center">
                                                {!! QrCode::size(50)->generate($produk->kode) !!}
                                            </div>
                                        </td>
                                        <td class="row d-flex align-items-center">
                                            {{-- <a href="/produk/{{ $produk->id }}/barcode" class="mx-auto"
                                                style="color: blue"><i class="fas fa-barcode"></i></i></a> --}}
                                            <a href="/produk/{{ $produk->id }}/update" class="mx-auto"
                                                style="color: blue"><i class="fas fa-edit"></i></a>
                                            <form class="mx-auto" action="produk/{{ $produk->id }}" method="POST"
                                                class="pr-2">
                                                @csrf
                                                @method('delete')
                                                <button class="p-0 border-0 btn" style="color: blue;" type="submit"
                                                    onclick="return confirm('Apakah anda yakin?')"><i
                                                        class="fas fa-times"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
