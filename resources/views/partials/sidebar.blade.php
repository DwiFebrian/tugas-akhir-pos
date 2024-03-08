<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="..."
                        class="avatar-img rounded-circle">
                </div>
                <div class="info">
                    <a data-toggle="collapse" aria-expanded="true">
                        {{-- <span>
                            {{ auth()->user()->nama }}
                            <span class="user-level">{{ auth()->user()->role }}</span>
                        </span> --}}
                        <span>
                            @if (Auth::user()->role === 'admin')
                                {{ auth()->user()->nama }}
                                <span class="user-level">Administrator</span>
                            @elseif(Auth::user()->role === 'kasir')
                                {{ auth()->user()->nama }}
                                <span class="user-level">Kasir</span>
                            @else
                                {{ auth()->user()->nama }}
                                <span class="user-level">Staff Gudang</span>
                            @endif
                        </span>
                    </a>
                </div>
            </div>
            @if (Auth::user()->role === 'admin')
                <ul class="nav nav-primary">
                    <li class="nav-item">
                        <a href="/dashboard">
                            {{-- <i class="fa-solid fa-house"></i> --}}
                            <span class="sub-item">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/kasir">
                            <span class="sub-item">Kasir</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/penjualan">
                            <span class="sub-item">Penjualan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/detail-penjualan">
                            <span class="sub-item">Detail Penjualan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/kategori">
                            <span class="sub-item">Kategori</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/produk">
                            <span class="sub-item">Produk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/keuangan">
                            <span class="sub-item">Keuangan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/user">
                            <span class="sub-item">Users</span>
                        </a>
                    </li>
                </ul>
            @elseif(Auth::user()->role === 'kasir')
                <ul class="nav nav-primary">
                    <li class="nav-item">
                        <a href="/dashboard">
                            {{-- <i class="fa-solid fa-house"></i> --}}
                            <span class="sub-item">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/kasir">
                            <span class="sub-item">Kasir</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/penjualan">
                            <span class="sub-item">Penjualan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/detail-penjualan">
                            <span class="sub-item">Detail Penjualan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/keuangan">
                            <span class="sub-item">Keuangan</span>
                        </a>
                    </li>
                </ul>
            @else
                <ul class="nav nav-primary">
                    <li class="nav-item">
                        <a href="/dashboard">
                            {{-- <i class="fa-solid fa-house"></i> --}}
                            <span class="sub-item">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/kategori">
                            <span class="sub-item">Kategori</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/produk">
                            <span class="sub-item">Produk</span>
                        </a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</div>
<!-- End Sidebar -->
