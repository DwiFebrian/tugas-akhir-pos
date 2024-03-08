@extends('layouts.main')

<?php
$sum = 0;
if (session()->has('cart')) {
    foreach (session('cart') as $key => $value) {
        $sum += $value['harga'] * $value['qty'];
    }
}
?>
@section('script')
@endsection

@section('container')
    <div class="page-inner">
        @if (session()->has('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong> {{ session('error') }}</strong>
                <button type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-md-8">
                <form method="post" action="kasir/actKasir" class="form-inline">
                    @csrf
                    <div class="input-group">
                        {{-- <select class="js-states form-control select2-search__field" id="produk" name="produk"
                            placeholder="Kode/Nama Produk"></select> --}}
                        <input type="text" id="produk" class="js-states form-control" name="produk"
                            autocomplete="select2" placeholder="Kode/Nama Produk" autofocus>
                        <input type="number" class="form-control" name="qty" min="1" value="1"
                            placeholder="Jumlah">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">Tambah</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <form method="post" action="/kasir/updateKasir">
                    @csrf
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th class="col-md-3">Jumlah</th>
                                <th>Sub Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $key => $cart)
                                <tr id="row_{{ $key }}">
                                    <td>{{ $cart['nama'] }}</td>
                                    <td>Rp. {{ number_format($cart['harga']) }}</td>
                                    <td>
                                        <input type="number" name="qty[{{ $key }}]" id="qty_{{ $key }}"
                                            value="{{ $cart['qty'] }}" data-harga="{{ $cart['harga'] }}"
                                            data-key="{{ $key }}" oninput="updateTotalHarga(this)">
                                    </td>
                                    <td><text class="h6">Rp.
                                            {{ number_format($cart['qty'] * $cart['harga']) }} <text></td>

                                    <td>
                                        <a href="javascript:void(0);" onclick="deleteRow('{{ $key }}')"
                                            class="btn btn-danger">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="/kasir/resetKasir" class="btn btn-success">Reset Keranjang</a>
                </form>
            </div>
            <div class="col-md-4">
                <h3>
                    <?php
                    echo date('l') . ', ' . date('d-M-Y');
                    ?>
                </h3>
                <h3 id="totalHarga">Total : Rp. <?= number_format($sum) ?></h3>
                <input type="number" class="form-control" name="bayar" id="bayar" placeholder="Jumlah Dibayar">
                <h3 id="kembalian">Kembalian : Rp. </h3>
                <form action="/kasir/simpan" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        // fungsi untuk membuat format rupiah pda data yang ditampilkan
        function formatRupiah(angka) {
            var number_string = angka.toString().replace(/\./g, '');
            var split = number_string.split(',');
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return ' ' + rupiah;
        }

        // fungsi untuk mengupdate kembalian ketika ada perubahan
        function updateKembalian() {
            var totalHargaElement = document.getElementById('totalHarga');
            var totalHarga = parseFloat(totalHargaElement.innerText.replace('Total: Rp ', '').replace(/\./g, '').replace(
                ',', '.'));

            var bayarElement = document.getElementById('bayar');
            var bayar = parseFloat(bayarElement.value);

            var kembalian = bayar - totalHarga;

            var kembalianElement = document.getElementById('kembalian');
            var kembalianFormatted = formatRupiah(Math.max(0, kembalian)); // Jangan tampilkan nilai negatif
            kembalianElement.innerText = 'Kembalian: Rp ' + kembalianFormatted;
        }

        // tambah event listener pada bayar input element
        var bayarElement = document.getElementById('bayar');
        bayarElement.addEventListener('input', function() {
            updateKembalian();
        });

        // Initial update of kembalian
        updateKembalian();

        function deleteRow(rowId) {
            var row = document.getElementById('row_' + rowId);
            row.parentNode.removeChild(row);

            // Menghitung total ulang dan mengganti total yang lama dengan total yang baru
            var subtotals = document.querySelectorAll('.subtotal');
            var total = 0;

            subtotals.forEach(function(subtotal) {
                var subtotalValue = parseFloat(subtotal.textContent.replace('Rp. ', '').replace(/\./g, '').replace(
                    ',', '.'));
                if (!isNaN(subtotalValue)) {
                    total += subtotalValue;
                }
            });
        }

        function updateTotalHarga(inputElement) {
            var total = 0;

            // Menghitung ulang total harga dari semua item
            var inputElements = document.querySelectorAll('[name^="qty["]');
            inputElements.forEach(function(inputElement) {
                var inputValue = parseInt(inputElement.value);
                var hargaPerItem = parseFloat(inputElement.getAttribute('data-harga'));
                var key = inputElement.getAttribute('data-key');

                var totalHargaPerItem = inputValue * hargaPerItem;
                var totalHargaPerItemFormatted = formatRupiah(totalHargaPerItem);

                var hargaElement = document.querySelector(`#row_${key} .h6`);
                hargaElement.innerText = 'Rp ' + totalHargaPerItemFormatted;

                total += totalHargaPerItem;
            });

            // Memperbarui tampilan total harga dengan format Rupiah
            var totalHargaFormatted = formatRupiah(total);
            var totalHargaElement = document.getElementById('totalHarga');
            totalHargaElement.innerText = 'Total: Rp ' + totalHargaFormatted;

            // Memperbarui kembali kembalian setelah mengubah qty
            updateKembalian();
        }

        // Add event listener to each input
        const inputElements = document.querySelectorAll('[name^="qty["]');
        inputElements.forEach(function(inputElement) {
            inputElement.addEventListener('input', function() {
                updateTotalHarga(this);
                updateKembalian();
            });
        });

        // Initial update of total and kembalian
        updateTotalHarga();
        updateKembalian();

        function deleteRow(rowId) {
            var row = document.getElementById('row_' + rowId);
            row.parentNode.removeChild(row);

            // Menghitung total ulang dan mengganti total yang lama dengan total yang baru
            updateTotalHarga();

            // Update kembalian setelah menghapus item
            updateKembalian();
        }

        // $(document).ready(function() {
        //     // Inisialisasi select2
        //     $('#produk').select2({
        //         placeholder: 'Kode/Nama Produk',
        //         tags: true, // Aktifkan dukungan tag
        //         ajax: {
        //             url: '/getProducts',
        //             dataType: 'json',
        //             delay: 250,
        //             processResults: function(data) {
        //                 return {
        //                     results: data
        //                 };
        //             },
        //             cache: true
        //         }
        //     });

        //     $('#produk').on('select2:open', function() {
        //         $('.select2-results li').on('keydown', function(e) {
        //             if (e.which === 13) {
        //                 e.preventDefault();
        //             }
        //         });
        //     });

        //     // Sesuaikan lebar dropdown agar terlihat lebih baik
        //     $('#produk').on('select2:open', function() {
        //         $('.select2-search__field').css('width', '100%');
        //     });

        // });


        //................................................................................................
        function updateTotalHargaServer(cartItems) {
            // Kirim permintaan Ajax ke route /kasir/recalculate
            axios.post('/kasir/recalculate', {
                    cartItems
                })
                .then(response => {
                    const {
                        totalItem,
                        totalHarga
                    } = response.data;

                    // Update nilai totalItem dan totalHarga di tampilan
                    document.getElementById('totalItem').innerText = totalItem;
                    document.getElementById('totalHarga').innerText = 'Total : Rp. ' + formatRupiah(totalHarga);

                    // Perbarui kembali kembalian setelah perubahan
                    updateKembalian();
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // ...

        // Fungsi untuk mengubah jumlah (qty) produk
        function updateQty(inputElement) {
            const key = inputElement.getAttribute('data-key');
            const qty = parseInt(inputElement.value);
            const harga = parseFloat(inputElement.getAttribute('data-harga'));
            const subtotal = qty * harga; // Hitung subtotal baru

            // Update nilai subtotal pada tampilan
            const subtotalElement = document.querySelector(`#row_${key} .h6`);
            subtotalElement.innerText = 'Rp ' + formatRupiah(subtotal);

            // Panggil fungsi untuk menghitung ulang di server
            updateTotalHargaServer([{
                key,
                qty,
                harga,
                subtotal
            }]);
        }

        // ...

        // Add event listener to each quantity input
        inputElements.forEach(function(inputElement) {
            inputElement.addEventListener('input', function() {
                updateQty(this);
                updateKembalian();
            });
        });
        // ...

        // Initial update of total and kembalian
        updateTotalHarga();
        updateKembalian();
    </script>
@endsection

{{-- @section('script')
    <script>
        // fungsi untuk membuat format rupiah pda data yang ditampilkan
        function formatRupiah(angka) {
            var number_string = angka.toString().replace(/\./g, '');
            var split = number_string.split(',');
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return ' ' + rupiah;
        }

        // fungsi untuk mengupdate kembalian ketika ada perubahan
        function updateKembalian() {
            var totalHargaElement = document.getElementById('totalHarga');
            var totalHarga = parseFloat(totalHargaElement.innerText.replace('Total: Rp ', '').replace(/\./g, '').replace(
                ',', '.'));

            var bayarElement = document.getElementById('bayar');
            var bayar = parseFloat(bayarElement.value);

            var kembalian = bayar - totalHarga;

            var kembalianElement = document.getElementById('kembalian');
            var kembalianFormatted = formatRupiah(Math.max(0, kembalian)); // Jangan tampilkan nilai negatif
            kembalianElement.innerText = 'Kembalian: Rp ' + kembalianFormatted;
        }

        // tambah event listener pada bayar input element
        var bayarElement = document.getElementById('bayar');
        bayarElement.addEventListener('input', function() {
            updateKembalian();
        });

        // Initial update of kembalian
        updateKembalian();

        function deleteRow(rowId) {
            var row = document.getElementById('row_' + rowId);
            row.parentNode.removeChild(row);

            // Menghitung total ulang dan mengganti total yang lama dengan total yang baru
            var subtotals = document.querySelectorAll('.subtotal');
            var total = 0;

            subtotals.forEach(function(subtotal) {
                var subtotalValue = parseFloat(subtotal.textContent.replace('Rp. ', '').replace(/\./g, '').replace(
                    ',', '.'));
                if (!isNaN(subtotalValue)) {
                    total += subtotalValue;
                }
            });
        }

        function updateTotalHarga(inputElement) {
            var total = 0;

            // Menghitung ulang total harga dari semua item
            var inputElements = document.querySelectorAll('[name^="qty["]');
            inputElements.forEach(function(inputElement) {
                var inputValue = parseInt(inputElement.value);
                var hargaPerItem = parseFloat(inputElement.getAttribute('data-harga'));
                var key = inputElement.getAttribute('data-key');

                var totalHargaPerItem = inputValue * hargaPerItem;
                var totalHargaPerItemFormatted = formatRupiah(totalHargaPerItem);

                var hargaElement = document.querySelector(`#row_${key} .h6`);
                hargaElement.innerText = 'Rp ' + totalHargaPerItemFormatted;

                total += totalHargaPerItem;
            });

            // Memperbarui tampilan total harga dengan format Rupiah
            var totalHargaFormatted = formatRupiah(total);
            var totalHargaElement = document.getElementById('totalHarga');
            totalHargaElement.innerText = 'Total: Rp ' + totalHargaFormatted;

            // Memperbarui kembali kembalian setelah mengubah qty
            updateKembalian();
        }

        // Add event listener to each input
        const inputElements = document.querySelectorAll('[name^="qty["]');
        inputElements.forEach(function(inputElement) {
            inputElement.addEventListener('input', function() {
                updateTotalHarga(this);
                updateKembalian();
            });
        });

        // Initial update of total and kembalian
        updateTotalHarga();
        updateKembalian();

        function deleteRow(rowId) {
            var row = document.getElementById('row_' + rowId);
            row.parentNode.removeChild(row);

            // Menghitung total ulang dan mengganti total yang lama dengan total yang baru
            updateTotalHarga();

            // Update kembalian setelah menghapus item
            updateKembalian();
        }

        $(document).ready(function() {
            // Inisialisasi Select2 pada input produk
            $('input[name="produk"]').select2({
                placeholder: 'Kode/Nama Produk',
                tags: true,
                ajax: {
                    url: '/getProducts',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Buat tampilan lebih baik dengan mengatur ukuran dropdown
            $('input[name="produk"]').on('select2:open', function() {
                $('.select2-search__field').css('width', '100%');
            });
        });

        //................................................................................................
        function updateTotalHargaServer(cartItems) {
            // Kirim permintaan Ajax ke route /kasir/recalculate
            axios.post('/kasir/recalculate', {
                    cartItems
                })
                .then(response => {
                    const {
                        totalItem,
                        totalHarga
                    } = response.data;

                    // Update nilai totalItem dan totalHarga di tampilan
                    document.getElementById('totalItem').innerText = totalItem;
                    document.getElementById('totalHarga').innerText = 'Total : Rp. ' + formatRupiah(totalHarga);

                    // Perbarui kembali kembalian setelah perubahan
                    updateKembalian();
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // ...

        // Fungsi untuk mengubah jumlah (qty) produk
        function updateQty(inputElement) {
            const key = inputElement.getAttribute('data-key');
            const qty = parseInt(inputElement.value);
            const harga = parseFloat(inputElement.getAttribute('data-harga'));
            const subtotal = qty * harga; // Hitung subtotal baru

            // Update nilai subtotal pada tampilan
            const subtotalElement = document.querySelector(`#row_${key} .h6`);
            subtotalElement.innerText = 'Rp ' + formatRupiah(subtotal);

            // Panggil fungsi untuk menghitung ulang di server
            updateTotalHargaServer([{
                key,
                qty,
                harga,
                subtotal
            }]);
        }

        // ...

        // Add event listener to each quantity input
        inputElements.forEach(function(inputElement) {
            inputElement.addEventListener('input', function() {
                updateQty(this);
                updateKembalian();
            });
        });
        // ...

        // Initial update of total and kembalian
        updateTotalHarga();
        updateKembalian();
    </script>
@endsection --}}
