@extends('layouts.owner.app')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Data Riwayat Bahan Baku
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <div class="dropdown relative ml-auto sm:ml-0">
            <button class="dropdown-toggle button px-2 box text-gray-700">
                <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
            </button>
            <div class="dropdown-box mt-10 absolute w-40 top-0 right-0 z-20">
                <div class="dropdown-box__content box p-2">
                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:text-green-700 rounded-md"> <i data-feather="file-plus" class="w-4 h-4 mr-2"></i> Export Excel </a>
                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:text-red-700 rounded-md"> <i data-feather="file-plus" class="w-4 h-4 mr-2"></i> Export PDF </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN: Datatable -->
<div class="intro-y datatable-wrapper box p-5 mt-5">
    <table class="table table-report table-report--bordered display datatable w-full">
        <thead>
            <tr>
                <th class="border-b-2 text-center whitespace-no-wrap">Riwayat</th>
                <th class="border-b-2 text-center whitespace-no-wrap">Tipe</th>
                <th class="border-b-2 text-center whitespace-no-wrap">JUMLAH</th>
                <th class="border-b-2 text-center whitespace-no-wrap">SATUAN</th>
                <th class="border-b-2 text-center whitespace-no-wrap">TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($histories as $history)
                <tr>
                    <td class="border-b">
                        <div class="font-medium whitespace-no-wrap">{!! $history->history !!}</div>
                    </td>
                    <td class="text-center border-b {{ $history->type == "Pengurangan" ? "text-red-700" : "text-green-500" }}">{{ $history->type }}</td>
                    <td class="text-center border-b">{{ $history->quantity }}</td>
                    <td class="text-center border-b">{{ $history->ProductOwner->unit }}</td>
                    <td class="text-center border-b">{{ $history->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- END: Datatable -->
@endsection