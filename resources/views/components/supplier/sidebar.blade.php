<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Midone Tailwind HTML Admin Template" class="w-6" src="/assets/images/logo.svg">
        <span class="hidden xl:block text-white text-lg ml-3"> Sim<span class="font-medium">Pedak</span> </span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>
            <a href="{{ route("supplier.dashboard.index") }}" class="side-menu {{ Route::is("supplier.dashboard.index") ? "side-menu--active" : "" }}">
                <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li>
        <li>
            <a href="{{ route("supplier.integration.index") }}" class="side-menu {{ Route::is("supplier.integration.index") ? "side-menu--active" : "" }}">
                <div class="side-menu__icon"> <i data-feather="message-square"></i> </div>
                <div class="side-menu__title"> Integrasi </div>
            </a>
        </li>
    </ul>
</nav>