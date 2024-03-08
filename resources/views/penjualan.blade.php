@extends('layouts.main')

@section('container')
    <div class="page-inner">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Penjualan</h4>

                        {{-- <a href="/produk/createProduk" class="btn btn-round ml-auto mb-3 btn-primary"><i
                                class="fa fa-plus"></i> Tambah
                        </a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>ID User</th>
                                    <th>Total Item</th>
                                    <th>Total Harga</th>
                                    {{-- <th style="width: 10%">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penjualans as $penjualan)
                                    <tr class="text-center">
                                        <td>{{ $penjualan->id_user }}</td>
                                        <td>{{ $penjualan->total_item }}</td>
                                        <td>{{ $penjualan->total_harga }}</td>
                                        {{-- <td class="row d-flex align-items-center">
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
                                        </td> --}}
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
