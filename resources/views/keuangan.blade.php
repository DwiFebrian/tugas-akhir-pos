@extends('layouts.main')

@section('container')
    <div class="page-inner">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Keuangan</h4>
                        <a href="/keuangan/createKeuangan" class="btn btn-round ml-auto mb-3 btn-primary"><i
                                class="fa fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <form method="get" action="/filter">
                                    <div class="row pb-3">
                                        <div class="col-md-3">
                                            <label for=""> Start Date :</label>
                                            <input type="date" name="start_date">
                                        </div>
                                        <div class="col-md-3">
                                            <label for=""> End Date :</label>
                                            <input type="date" name="end_date">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keuangans as $keuangan)
                                    <tr class="text-center">
                                        <td>{{ $keuangan->jenis }}</td>
                                        <td>{{ $keuangan->keterangan }}</td>
                                        <td>{{ $keuangan->jumlah }}</td>
                                        <td>{{ $keuangan->created_at }}</td>
                                        <td class="row d-flex align-items-center">
                                            <a href="/keuangan/{{ $keuangan->id }}/update" class="mx-auto"
                                                style="color: blue"><i class="fas fa-edit"></i></a>
                                            <form class="mx-auto" action="keuangan/{{ $keuangan->id }}" method="POST"
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButton = document.getElementById('filterButton');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const dataTable = $('#filtered-datatables').DataTable();

            filterButton.addEventListener('click', function() {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                // Buat permintaan AJAX
                $.ajax({
                    url: `/keuangan/filter`,
                    method: 'GET',
                    success: function(data) {
                        // Perbarui data dalam tabel
                        dataTable.clear().rows.add(data).draw();
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });
        });
    </script>
@endsection
