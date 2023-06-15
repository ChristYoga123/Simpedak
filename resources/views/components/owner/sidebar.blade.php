<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Midone Tailwind HTML Admin Template" class="w-6" src="/assets/images/logo.svg">
        <span class="hidden xl:block text-white text-lg ml-3"> Sim<span class="font-medium">Pedak</span> </span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>
            <a href="{{ route("owner.dashboard.index") }}" class="side-menu {{ Route::is("owner.dashboard.index") ? "side-menu--active" : "" }}">
                <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="side-menu {{ Route::is("owner.product.*") ? "side-menu--active" : "" }}">
                <div class="side-menu__icon"> <i data-feather="box"></i> </div>
                <div class="side-menu__title"> Manajemen Stok <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{ route("owner.product.raw-product.index") }}" class="side-menu {{ Route::is("owner.product.raw-product.*") ? "side-menu--active" : "" }}">
                        <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                        <div class="side-menu__title"> Bahan Baku </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route("owner.product.serve-product.index") }}" class="side-menu {{ Route::is("owner.product.serve-product.*") ? "side-menu--active" : "" }}">
                        <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                        <div class="side-menu__title"> Produk Jadi </div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route("owner.transaction.index") }}" class="side-menu {{ Route::is("owner.transaction.*") ? "side-menu--active" : "" }}">
                <div class="side-menu__icon"> <i data-feather="inbox"></i> </div>
                <div class="side-menu__title"> Kasir </div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="side-menu {{ Route::is("owner.integration.*") ? "side-menu--active" : "" }}">
                <div class="side-menu__icon"> <i data-feather="message-square"></i> </div>
                <div class="side-menu__title"> Integrasi <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
            </a>
            <ul>
                <li>
                    <a href="{{ route("owner.integration.index") }}" class="side-menu {{ Route::is("owner.integration.index") ? "side-menu--active" : "" }}">
                        <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                        <div class="side-menu__title"> Kerja Sama </div>
                    </a>
                </li>
                <li>
                    <a href="/chatify" class="side-menu">
                        <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                        <div class="side-menu__title"> Chat </div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route("owner.jadwal.index") }}" class="side-menu {{ Route::is("owner.jadwal.*" ? "side-menu--active" : "") }}">
                <div class="side-menu__icon"> <i data-feather="clock"></i> </div>
                <div class="side-menu__title"> Penjadwalan </div>
            </a>
        </li>
    </ul>
</nav>