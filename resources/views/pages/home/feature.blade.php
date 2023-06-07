@extends('layouts.home.app')

@section('content')
<section id="feature" class="w-full h-screen flex flex-row justify-center items-center gap-24">
    <div class="feature-image hidden lg:flex">
        <img src="{{ asset("images/detail-fitur.png") }}" alt="">
    </div>

    <div class="list-feature w-96 p-5 shadow shadow-lg h-[300px]">
        <div class="flex justify-between">
            <p class="font-bold">Paket Bundling</p>
            <p class="font-semibold">Rp200.000,00</p>
        </div>
        <a href="{{ route("home.register") }}"><button class="btn w-full bg-[#3A00E5] border-0 my-5">Beli Fitur</button></a>
        <div class="flex flex-col gap-5">
            <div class="flex gap-8">
                <img src="{{ asset("images/beenhere.png") }}" alt="">
                <p>Akses penuh semua fitur utama</p>
            </div>

            <div class="flex gap-8">
                <img src="{{ asset("images/beenhere.png") }}" alt="">
                <p>Akses penuh semua fitur utama</p>
            </div>

            <div class="flex gap-8">
                <img src="{{ asset("images/beenhere.png") }}" alt="">
                <p>Akses penuh semua fitur utama</p>
            </div>
        </div>
    </div>
</section>

@include('components.home.footer')
@endsection