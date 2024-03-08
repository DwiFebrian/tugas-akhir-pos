@extends('layouts.main')

@section('container')
    <div class="page-inner">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Kategori</h4>
                        <a href="/kategori/createKategori" class="btn btn-round ml-auto mb-3 btn-primary"><i
                                class="fa fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 15%">ID Kategori</th>
                                    <th>Kategori</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $kategori)
                                    <tr class="text-center">
                                        <td>{{ $kategori->id }}</td>
                                        <td>{{ $kategori->nama }}</td>
                                        <td class="row d-flex align-items-center">
                                            <a href="/kategori/{{ $kategori->id }}/update" class="mx-auto"
                                                style="color: blue"><i class="fas fa-edit"></i></a>
                                            <form class="mx-auto" action="kategori/{{ $kategori->id }}" method="POST"
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
