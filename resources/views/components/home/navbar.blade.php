<div class="navbar md:px-10 md:py-4 bg-base-100 fixed w-full z-[999999]">
    <div class="navbar-start">
        <div class="dropdown">
        <label tabindex="0" class="btn btn-ghost lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
        </label>
        <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
            <li class="font-semibold"><a href="/" class="text-[#3A00E5]">Beranda</a></li>
            <li class="font-semibold"><a href="#fitur" class="text-[#3A00E5]">Fitur</a></li>
            <li class="font-semibold"><a href="#testimoni" class="text-[#3A00E5]">Testimoni</a></li>
            <li class="font-semibold"><a href="#kontak" class="text-[#3A00E5]">Kontak</a></li>
        </ul>
        </div>
        <a href="/" class="btn btn-ghost normal-case text-xl">
            <img src="{{ asset("images/logo.png") }}" alt="">
        </a>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li class="font-semibold text-[#3A00E5]"><a href="/">Beranda</a></li>
            <li class="font-semibold text-[#3A00E5]"><a href="#fitur">Fitur</a></li>
            <li class="font-semibold text-[#3A00E5]"><a href="#testimoni">Testimoni</a></li>
            <li class="font-semibold text-[#3A00E5]"><a href="#kontak">Kontak</a></li>
        </ul>
    </div>
    <div class="navbar-end">
        @auth
            @if (Auth::user()->ClientTransactions)
                @if (Auth::user()->ClientTransactions->payment_status =="paid")
                    @if (Auth::user()->hasRole("Owner"))
                        <a href="{{ route("owner.dashboard.index") }}" class="btn bg-[#3A00E5] rounded-2xl border-0">Masuk Dashboard Owner</a>
                    @elseif (Auth::user()->hasRole("Supplier"))
                        <a href="{{ route("supplier.dashboard.index") }}" class="btn bg-[#3A00E5] rounded-2xl border-0">Masuk Dashboard Supplier</a>
                    @endif
                @else
                    <a href="btn bg-[#3A00E5] rounded-2xl border-0">Lanjutkan Pembayaran</a>
                @endif
            @endif
        @endauth
        @guest
            <a href="{{ route("home.register") }}"><button class="btn bg-[#3A00E5] rounded-2xl border-0">Masuk</button></a>
        @endguest
    </div>
</div>