@extends("layouts.owner.app")

@section('content')
<!-- END: Top Bar -->
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Profile Layout
    </h2>
</div>
<!-- BEGIN: Profile Info -->
<div class="intro-y box px-5 pt-5 mt-5">
    <div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
        <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
            <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="{{ $supplier->getFirstMediaUrl("avatar") }}">
                <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-theme-1 rounded-full p-2"> <i class="w-4 h-4 text-white" data-feather="camera"></i> </div>
            </div>
            <div class="ml-5">
                <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ $supplier->name }}</div>
                <div class="text-gray-600">{{ $supplier->Business->name }}</div>
            </div>
        </div>
        <div class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-200 border-t lg:border-t-0 pt-5 lg:pt-0">
            <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail" class="w-4 h-4 mr-2"></i> {{ $supplier->email }} </div>
            <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-feather="phone" class="w-4 h-4 mr-2"></i> {{ $supplier->Business->contact }} </div>
            <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-feather="map" class="w-4 h-4 mr-2"></i> {{ $supplier->Business->address }} </div>
        </div>
    </div>

    <p class="text-lg font-semibold">Galeri Perusahaan</p>

    <div class="mt-5 flex flex-col lg:flex-row gap-3 flex-wrap">
        @foreach ($supplier->getMedia("business-galleries") as $gallery)
            <img src="{{ $gallery->getUrl() }}" alt="" width="200px">
        @endforeach
    </div>
</div>
<!-- END: Profile Info -->
@endsection