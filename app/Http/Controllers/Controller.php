<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Keuangan;
use App\Models\Kategori;
use App\Models\Detail_Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class Controller extends BaseController
{

    // LOGIN =============================================================================
    public function login()
    {
        return view('login');
    }

    public function loginAct(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Proses autentikasi setelah validasi berhasil
        if (Auth::attempt($validatedData)) {
            $request->session()->regenerate();

            // Setelah autentikasi berhasil
            $user = Auth::user(); // Mengambil data pengguna yang terautentikasi

            // Menyimpan peran pengguna dalam session
            Session::put('role', $user->role);
            return redirect('/dashboard');
        } else {
            // Autentikasi gagal
            return redirect()->back()->withInput()->withErrors(['email' => 'Email atau kata sandi salah']);
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }


    // DASHBOARD =========================================================================

    // Fungsi untuk menampilkan halaman dashboard dan mengambil beberapa data dari database
    public function readDashboard()
    {
        $today = Carbon::now()->format('Y-m-d');
        $totalProduk = Produk::count();
        $transaksi = Penjualan::whereDate('created_at', $today)->count();
        $produkMenipis = Produk::where('stok', '<', 10)->get();
        $produkKadaluwarsa = Produk::where('exp_date', '<', Carbon::now()->addDays(10))->get();

        // Ambil data keuangan jenis 'masuk' yang ditambahkan hari ini
        $dataKeuanganMasuk = Keuangan::whereDate('created_at', $today)
            ->where('jenis', 'masuk')
            ->sum('jumlah');

        // Ambil data keuangan jenis 'keluar' yang ditambahkan hari ini
        $dataKeuanganKeluar = Keuangan::whereDate('created_at', $today)
            ->where('jenis', 'keluar')
            ->sum('jumlah');

        // Hitung subtotal dari kolom "jumlah" dari data keuangan hari ini
        $totalJumlah = $dataKeuanganMasuk - $dataKeuanganKeluar;
        return view('dashboard', compact(
            'totalProduk',
            'transaksi',
            'totalJumlah',
            'produkMenipis',
            'produkKadaluwarsa',
            'dataKeuanganMasuk',
            'dataKeuanganKeluar'
        ));
    }


    // KASIR =============================================================================

    // Fungsi untuk menampilkan halaman kasir dan mengambil beberapa data dari database
    public function readKasir()
    {
        $produk = Produk::all();
        $cartItems = session('cart', []);

        return view('kasir', compact('produk', 'cartItems'));
    }

    // Fungsi untuk mencari data yang diinputkan dan memasukkan data tersebut ke tabel cart
    public function actKasir(Request $request)
    {
        $produkInput = $request->input('produk');
        $qty = $request->input('qty');

        // Mencari produk berdasarkan nama atau kode
        $produk = Produk::where('nama', 'LIKE', '%' . $produkInput . '%')
            ->orWhere('kode', 'LIKE', '%' . $produkInput . '%')
            ->first();

        if ($produk) {
            $cart = Session::get('cart', []);
            if (isset($cart[$produk->id])) {
                return redirect('/kasir')->with('error', 'Ketikkan nama/kode produk yang belum ada di tabel!');
            } else {
                $cart[$produk->id] = [
                    'id' => $produk->id,
                    'nama' => $produk->nama,
                    'harga' => $produk->harga,
                    'qty' => $qty,
                ];
            }
            Session::put('cart', $cart);
        } else {
            return redirect('/kasir')->with('error', 'Produk tidak ditemukan!');
        }

        return redirect('/kasir');
    }

    // Fungsi untuk mereset tabel cart
    public function resetKasir()
    {
        Session::forget('cart');
        return redirect('/kasir');
    }

    // Fungsi untuk menghapus salah satu baris di tabel cart
    // public function deleteKasir($id)
    // {
    //     $cart = Session::get('cart', []);
    //     $filteredCart = array_filter($cart, function ($var) use ($id) {
    //         return ($var['id'] == $id);
    //     });

    //     foreach ($filteredCart as $key => $value) {
    //         unset($cart[$key]);
    //     }

    //     Session::put('cart', array_values($cart));

    //     return redirect('kasir');
    // }

    // Fungsi untuk memperbarui jumlah qty pada tabel cart
    // public function updateKasir(Request $request)
    // {
    //     $qty = $request->input('qty');

    //     $cart = Session::get('cart', []);

    //     foreach ($cart as $key => $value) {
    //         if (isset($qty[$key])) {
    //             $cart[$key]['qty'] = $qty[$key];
    //         } else {
    //             echo 'tidak ada';
    //         }
    //     }

    //     Session::put('cart', $cart);

    //     return redirect('kasir');
    // }

    // Fungsi untuk memasukkan data ke tabel penjualan
    // public function simpanPenjualan()
    // {
    //     // Ambil data cart dari session
    //     $cartItems = Session::get('cart', []);

    //     if (count($cartItems) === 0) {
    //         // Jika cart kosong, kembalikan ke halaman kasir
    //         return redirect('/kasir')->with('message', 'Cart kosong, tidak ada penjualan.');
    //     }

    //     // Hitung total item dan total harga penjualan
    //     $totalItem = 0;
    //     $totalHarga = 0;

    //     foreach ($cartItems as $cartItem) {
    //         $totalItem += $cartItem['qty'];
    //         $totalHarga += ($cartItem['harga'] * $cartItem['qty']);
    //     }

    //     // Simpan data penjualan ke tabel "penjualans"
    //     $penjualan = Penjualan::create([
    //         'total_item' => $totalItem,
    //         'total_harga' => $totalHarga,
    //     ]);

    //     // Simpan detail penjualan ke tabel "detail__penjualans"
    //     foreach ($cartItems as $cartItem) {
    //         Detail_Penjualan::create([
    //             'id_penjualan' => $penjualan->id,
    //             'nama' => $cartItem['nama'],
    //             'harga' => $cartItem['harga'],
    //             'jumlah' => $cartItem['qty'],
    //             'subtotal' => ($cartItem['harga'] * $cartItem['qty']),
    //         ]);
    //     }

    //     // Simpan subtotal penjualan ke tabel "keuangans"
    //     Keuangan::create([
    //         'jenis' => 'masuk',
    //         'keterangan' => 'Penjualan dari kasir',
    //         'jumlah' => $totalHarga,
    //     ]);

    //     // Kurangi stok produk di tabel "produk"
    //     foreach ($cartItems as $cartItem) {
    //         $produk = Produk::find($cartItem['id']);
    //         if ($produk) {
    //             // Pastikan stok produk tidak kurang dari nol
    //             $newStok = max($produk->stok - $cartItem['qty'], 0);
    //             $produk->stok = $newStok;
    //             $produk->save();
    //         }
    //     }

    //     // Hapus data cart dari session setelah berhasil disimpan ke database
    //     Session::forget('cart');

    //     // Redirect ke halaman kasir dengan pesan sukses
    //     return redirect('/kasir')->with('success', 'Penjualan berhasil disimpan ke database.');
    // }

    //................................................................................................
    public function recalculate(Request $request)
    {
        $cartItems = $request->input('cartItems'); // Data perubahan dari JavaScript

        $totalItem = 0;
        $totalHarga = 0;

        foreach ($cartItems as $cartItem) {
            $totalItem += $cartItem['qty'];
            $totalHarga += $cartItem['subtotal']; // Gunakan subtotal yang telah dihitung di JavaScript
        }

        return response()->json([
            'totalItem' => $totalItem,
            'totalHarga' => $totalHarga,
        ]);
    }

    public function simpanPenjualan(Request $request)
    {
        // Ambil data cart dari session
        $cartItems = Session::get('cart', []);

        if (count($cartItems) === 0) {
            // Jika cart kosong, kembalikan ke halaman kasir
            return redirect('/kasir')->with('message', 'Cart kosong, tidak ada penjualan.');
        }

        // Hitung ulang total item dan total harga penjualan
        $totalItem = 0;
        $totalHarga = 0;
        $user = $request->input('user');

        foreach ($cartItems as $cartItem) {
            $totalItem += $cartItem['qty'];
            $totalHarga += ($cartItem['harga'] * $cartItem['qty']);
        }

        // Simpan data penjualan ke tabel "penjualans"
        $penjualan = Penjualan::create([
            'id_user' => auth()->user()->id,
            'total_item' => $totalItem,
            'total_harga' => $totalHarga,
        ]);

        // Simpan detail penjualan ke tabel "detail_penjualans"
        foreach ($cartItems as $cartItem) {
            Detail_Penjualan::create([
                'id_penjualan' => $penjualan->id,
                'nama' => $cartItem['nama'],
                'harga' => $cartItem['harga'],
                'jumlah' => $cartItem['qty'],
                'subtotal' => ($cartItem['harga'] * $cartItem['qty']),
            ]);
        }

        // Simpan subtotal penjualan ke tabel "keuangans"
        Keuangan::create([
            'jenis' => 'masuk',
            'keterangan' => 'Penjualan dari kasir',
            'jumlah' => $totalHarga,
        ]);

        // Kurangi stok produk di tabel "produk"
        foreach ($cartItems as $cartItem) {
            $produk = Produk::find($cartItem['id']);
            if ($produk) {
                // Pastikan stok produk tidak kurang dari nol
                $newStok = max($produk->stok - $cartItem['qty'], 0);
                $produk->stok = $newStok;
                $produk->save();
            }
        }

        // Hapus data cart dari session setelah berhasil disimpan ke database
        Session::forget('cart');

        // Redirect ke halaman kasir dengan pesan sukses
        return redirect('/kasir')->with('success', 'Penjualan berhasil disimpan ke database.');
    }

    // PENJUALAN ============================================================================

    public function readPenjualan()
    {
        return view('penjualan', [
            'penjualans' => Penjualan::all()
        ]);
    }

    // DETAIL PENJUALAN =====================================================================

    public function readDPenjualan()
    {
        return view('dpenjualan', [
            'dpenjualans' => Detail_Penjualan::all()
        ]);
    }

    // KATEGORI ==========================================================================

    public function readKategori()
    {
        return view('kategori', [
            'kategoris' => Kategori::all()
        ]);
    }



    public function createKategori()
    {
        return view('create.createKategori');
    }

    public function storeKategori(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required'
        ]);

        Kategori::create($validatedData);

        return redirect('/kategori');
    }

    public function editKategori(Kategori $kategori, $id)
    {
        return view('update.editKategori', [
            'kategoris' => $kategori->findOrFail($id)
        ]);
    }

    public function updateKategori(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required'
        ]);

        Kategori::where('id', $id)->update($validatedData);

        return redirect('/kategori');
    }

    public function deleteKategori(Kategori $kategori, $id)
    {
        $kategori->where('id', $id)->delete();

        return redirect('/kategori');
    }

    // PRODUK ============================================================================

    public function readProduk()
    {
        return view('produk', [
            'produks' => Produk::all()
        ]);
    }

    public function createProduk()
    {
        return view('create.createProduk', [
            'kategoris' => Kategori::all()
        ]);
    }

    public function storeProduk(Request $request)
    {
        $validatedData = $request->validate([
            'id_kategori' => 'required',
            'kode' => 'required|max:13',
            'nama' => 'required|max:255',
            'harga' => 'required',
            'stok' => 'required',
            'exp_date' => ''
        ]);

        Produk::create($validatedData);

        return redirect('/produk');
    }

    public function editProduk(Produk $produk, $id)
    {
        return view('update.editProduk', [
            'produks' => $produk->findOrFail($id),
            'kategoris' => Kategori::all()
        ]);
    }

    public function updateProduk(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id_kategori' => 'required',
            'kode' => 'required|max:13',
            'nama' => 'required|max:255',
            'harga' => 'required',
            'stok' => 'required',
            'exp_date' => 'required'
        ]);

        Produk::where('id', $id)->update($validatedData);

        return redirect('/produk');
    }

    public function deleteProduk(Produk $produk, $id)
    {
        $produk->where('id', $id)->delete();

        return redirect('/produk');
    }

    // function generateBarcode($code)
    // {
    //     $generator = new BarcodeGeneratorPNG();
    //     $barcodeData = $generator->getBarcode($code, $generator::TYPE_CODE_128);

    //     return "data:image/png;base64," . base64_encode($barcodeData);
    // }


    // KEUANGAN =========================================================================

    public function readKeuangan()
    {
        return view('keuangan', [
            'keuangans' => Keuangan::all()
        ]);
    }

    public function createKeuangan()
    {
        return view('create.createKeuangan');
    }

    public function storeKeuangan(Request $request)
    {
        $validatedData = $request->validate([
            'jenis' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required'
        ]);

        Keuangan::create($validatedData);

        return redirect('/keuangan');
    }

    public function editKeuangan(Keuangan $keuangan, $id)
    {
        return view('update.editKeuangan', [
            'keuangans' => $keuangan->findOrFail($id),
        ]);
    }

    public function updateKeuangan(Request $request, $id)
    {
        $validatedData = $request->validate([
            'jenis' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required'
        ]);

        Keuangan::where('id', $id)->update($validatedData);

        return redirect('/keuangan');
    }

    public function deleteKeuangan(Keuangan $keuangan, $id)
    {
        $keuangan->where('id', $id)->delete();

        return redirect('/keuangan');
    }

    public function filter(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $keuangans = Keuangan::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();

        return view('keuangan', compact('keuangans'));
    }

    // USER ===============================================================
    public function readUser()
    {
        return view('user', [
            'users' => User::all()
        ]);
    }

    public function createUser()
    {
        return view('create.createUser');
    }

    public function storeUser(Request $request)
    {

        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required',
            'foto' => 'required|image',
            'role' => 'required'
        ]);

        $validatedData['foto'] = $request->file('foto')->store('fotos');

        $validatedData['password'] = bcrypt($validatedData['password']);
        // dd($validatedData);
        // User::create($validatedData);
        $user = new User();
        $user->nama = $validatedData['nama'];
        $user->email = $validatedData['email'];
        $user->password = $validatedData['password'];
        $user->foto = $validatedData['foto'];
        $user->role = $validatedData['role'];
        $user->save();

        return redirect('/user');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('update.editUser', [
            'user' => $user,
        ]);
    }


    public function updateUser(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required',
            'foto' => 'required|image',
            'role' => 'required'
        ]);

        if ($request->oldImage) {
            Storage::delete($request->oldImage);
        }

        $validatedData['foto'] = $request->file('foto')->store('fotos');

        $validatedData['password'] = bcrypt($validatedData['password']);
        // dd($validatedData);
        // User::create($validatedData);
        // User::where('id', $id)->update($validatedData);
        $user = new User();
        $user::where('id', $id)->update([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'foto' => $validatedData['foto'],
            'role' => $validatedData['role']
        ]);

        // $user->nama = $validatedData['nama'];
        // $user->email = $validatedData['email'];
        // $user->password = $validatedData['password'];
        // $user->foto = $validatedData['foto'];
        // $user->role = $validatedData['role'];
        // $user::where('id', $id)->update();

        return redirect('/user');
    }

    public function deleteUser(User $user, $id)
    {
        if ($user->find($id)->foto) {
            Storage::delete($user->find($id)->foto);
        }
        $user->where('id', $id)->delete();

        return redirect('/user');
    }
}
