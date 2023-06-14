@extends('layouts.home.app')

@section('content')
<!-- BEGIN: HERO --->
<section id="hero" class="w-full h-screen justify-center items-center">
    <div class="hero min-h-screen bg-[url('/public/images/bg-home.png')] bg-cover bg-[#3A00E5] mix-blend-normal">
        <div class="hero-content text-center text-white">
            <div>
                <h1 class="text-5xl font-bold">Selamat Datang</h1>
                <p class="py-6">
                    Selamat datang di website <strong>SIMPEDAK</strong>. Bergabunglah sebagai anggota kami dan <br>
                    nikmati akses eksklusif ke fitur-fitur yang dirancang khusus untuk meningkatkan <br>
                    kesuksesan bisnis peternakan Anda. Terima kasih atas kunjungan Anda!
                </p>
                <a href="{{ route("home.feature") }}" class="btn border-0 bg-[#E2D3F4] text-[#3A00E5]">Langganan Sekarang</a>
            </div>
        </div>
    </div>

    <div class="wave absolute z-[99999] w-full lg:-mt-[17rem]">
        <img src="{{ asset("images/wave.png") }}" class="w-full hidden lg:flex">
    </div>
</section>
<!-- END: HERO --->

<!-- BEGIN: CONTENT --->
<section id="about-us" class="w-full h-screen justify-center items-center mt-32 md:mt-72">
    <div class="flex flex-col gap-20">
        <p class="text-4xl font-bold text-center">Tentang Kami</p>
        <div class="flex gap-28 justify-center">
            <div class="flex flex-col gap-5 text-center ">
                <img src="{{ asset("images/about-1.png") }}" class="rounded-full mx-auto" width="150px">
                <p class="font-medium">
                    Bantu pengelolaan ternak <br>
                    Anda dengan baik
                </p>
            </div>

            <div class="flex flex-col gap-5 text-center">
                <img src="{{ asset("images/about-2.png") }}" class="rounded-full mx-auto" width="150px">
                <p class="font-medium">
                    Pertemukan Anda dengan <br>
                    supplier terpercaya
                </p>
            </div>

            <div class="flex flex-col gap-5 text-center">
                <img src="{{ asset("images/about-3.png") }}" class="rounded-full mx-auto" width="150px">
                <p class="font-medium">
                    Pantau stok gudang tanpa<br>
                    pencatatan manual
                </p>
            </div>
        </div>
    </div>

    <div class="mt-24 flex flex-col gap-20">
        <p class="text-4xl font-bold text-center">Fitur Kami</p>
        <div class="flex flex-col items-center lg:flex-row gap-3 justify-center">
            <div class="card card-compact w-96 bg-base-100 shadow-xl">
                <figure class=""><img src="{{ asset("images/fitur-1.png") }}" alt="Shoes" class="bg-cover" /></figure>
                <div class="card-body">
                    <h2 class="card-title">Fitur Manajemen Stok</h2>
                    <p class="text-justify text-lg">
                        Membantu anda mengelola stok
                        produk dan bahan baku agar
                        produksi lebih maksimal
                    </p>
                </div>
            </div>

            <div class="card card-compact w-96 bg-base-100 shadow-xl">
                <figure><img src="{{ asset("images/fitur-2.png") }}" alt="Shoes" class="bg-cover" /></figure>
                <div class="card-body">
                    <h2 class="card-title">Fitur Integrasi</h2>
                    <p class="text-justify text-lg">
                        Membantu anda menemukan 
                        supplier bahan baku terdekat
                        untuk cegah kurangnya
                        stok produk
                    </p>
                </div>
            </div>

            <div class="card card-compact w-96 bg-base-100 shadow-xl">
                <figure><img src="{{ asset("images/fitur-3.png") }}" alt="Shoes" class="bg-cover" /></figure>
                <div class="card-body">
                    <h2 class="card-title">Fitur Penjadwalan</h2>
                    <p class="text-justify text-lg">
                        Memberi ternak kepada anda
                        kapan ternak harus diberi pakan,
                        dikawinkan, dipanen, dll. Fitur ini
                        siap bantu ternak selalu sehat
                    </p>
                </div>
            </div>
        </div>
        <div class="w-full flex justify-center">
            <button class="btn bg-[#3A00E5] w-[785px]">Detail Fitur</button>
        </div>
    </div>

    <div class="mt-24 flex flex-col gap-20 mb-20">
        <p class="text-4xl font-bold text-center">Apa Kata Mereka?</p>
        <div class="flex flex-wrap justify-center gap-3">
            <div class="card w-[26rem] bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex gap-3">
                        <img src="{{ asset("images/logo.png") }}" class="rounded-full" width="50px">
                        <h2 class="card-title">Chintya Fitorus</h2>
                    </div>
                    <p class="mt-5 text-justify">"Sejak saya mulai menggunakan fitur premium, pengalaman saya dalam mengelola peternakan secara efisien dan efektif benar-benar meningkat pesat”</p>
                </div>
            </div>

            <div class="card w-[26rem] bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex gap-3">
                        <img src="{{ asset("images/logo.png") }}" class="rounded-full" width="50px">
                        <h2 class="card-title">M. Yoga</h2>
                    </div>
                    <p class="mt-5 text-justify">“Saya bisa melakukan pencatatan stok gudang dengan mudah, sehingga tidak terjadi kekurangan stok saat ada pembelian”</p>
                </div>
            </div>

            <div class="card w-[26rem] bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex gap-3">
                        <img src="{{ asset("images/logo.png") }}" class="rounded-full" width="50px">
                        <h2 class="card-title">Christianus Yahya</h2>
                    </div>
                    <p class="mt-5 text-justify">“Benar-benar memudahkan saya untuk mengelola bisnis pembuatan yougurt ini, saya bisa terhubung dengan supplier penghasil bahan baku dengan mudah tanpa takut  ditipu dengan kualitasnya”</p>
                </div>
            </div>

            <div class="card w-[26rem] bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex gap-3">
                        <img src="{{ asset("images/logo.png") }}" class="rounded-full" width="50px">
                        <h2 class="card-title">Ferli Wardhani</h2>
                    </div>
                    <p class="mt-5 text-justify">“Dukungan pelanggan yang disediakan oleh tim aplikasiluar biasa. Mereka responsif terhadap pertanyaan dan masalah yang saya hadapi“</p>
                </div>
            </div>

            <div class="card w-[26rem] bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex gap-3">
                        <img src="{{ asset("images/logo.png") }}" class="rounded-full" width="50px">
                        <h2 class="card-title">Amelia Dewi P</h2>
                    </div>
                    <p class="mt-5 text-justify">“Sebagai peternak baru fitur penjadwalan sangat cocok buat saya mengetahui kapan harus memberi makan ternak, dengan fitur ini kesehatan ternak saya bisa terjaga dengan baik.”</p>
                </div>
            </div>

            <div class="card w-[26rem] bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex gap-3">
                        <img src="{{ asset("images/logo.png") }}" class="rounded-full" width="50px">
                        <h2 class="card-title">Ilham Wibisono</h2>
                    </div>
                    <p class="mt-5 text-justify">“Proses pencatatan dll sudah tercover pada fitur di Simpedak, jadi saya bisa lebih fokus meningkatkan di bagian produksi”</p>
                </div>
            </div>
        </div>
    </div>

    @include('components.home.footer')
</section>
<!-- END: CONTENT --->

@endsection