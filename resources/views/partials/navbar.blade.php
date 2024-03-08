<!-- Navbar Header -->
<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

    <div class="container-fluid">
        <h2 class="pb-2 mt-3" style="color:#FFFFFF;">Selamat Datang, {{ auth()->user()->nama }}!</h2>
        <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">

            <form action="/logout" method="post">
                @csrf
                <button class="btn ms-auto" type="submit"
                    Style="Background-color:#BC9C22; color:#262C14;">Logout</button>
            </form>

        </ul>
    </div>
</nav>
<!-- End Navbar -->
