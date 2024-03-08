@extends('layouts.main')

@section('container')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-dark bg-primary-gradient">
                    <div class="card-body pb-0">
                        <h2 class="mb-2">{{ $totalProduk }}</h2>
                        <p>Jumlah Produk</p>
                        <div class="pull-in sparkline-fix chart-as-background">
                            <div id="lineChart"><canvas width="327" height="70"
                                    style="display: inline-block; width: 327px; height: 70px; vertical-align: top;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-dark bg-secondary-gradient">
                    <div class="card-body pb-0">
                        <h2 class="mb-2">Rp. {{ number_format($totalJumlah) }}</h2>
                        <p>Keuntungan Hari Ini</p>
                        <div class="pull-in sparkline-fix chart-as-background">
                            <div id="lineChart2"><canvas width="327" height="70"
                                    style="display: inline-block; width: 327px; height: 70px; vertical-align: top;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-dark bg-success2">
                    <div class="card-body pb-0">
                        <h2 class="mb-2">{{ $transaksi }}</h2>
                        <p>Transaksi Hari Ini</p>
                        <div class="pull-in sparkline-fix chart-as-background">
                            <div id="lineChart3"><canvas width="327" height="70"
                                    style="display: inline-block; width: 327px; height: 70px; vertical-align: top;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Grafik Keuangan</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="min-height: 375px">
                            <canvas id="keuanganChart"></canvas>
                        </div>
                        <div id="keuanganChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Produk Kadaluwarsa</th>
                                <th>Sisa Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produkKadaluwarsa as $produk)
                                <tr>
                                    <td>{{ $produk->nama }}</td>
                                    <td>{{ Carbon\Carbon::parse($produk->exp_date)->diffInDays(Carbon\Carbon::now()) }} hari
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Produk Menipis</th>
                                <th>Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produkMenipis as $produk)
                                <tr>
                                    <td>{{ $produk->nama }}</td>
                                    <td>{{ $produk->stok }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Mendapatkan data dari kontroller
        var dataKeuanganMasuk = {{ $dataKeuanganMasuk }};
        var dataKeuanganKeluar = {{ $dataKeuanganKeluar }};

        var keuanganChart = new Chart(document.getElementById("keuanganChart"), {
            type: 'bar',
            data: {
                labels: ['Keuangan Masuk', 'Keuangan Keluar'],
                datasets: [{
                    label: 'Jumlah',
                    data: [dataKeuanganMasuk, dataKeuanganKeluar],
                    backgroundColor: ['#36A2EB', '#FF6384']
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
