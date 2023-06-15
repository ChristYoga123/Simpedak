@extends('layouts.supplier.app')

@section('content')
<div class="intro-y flex flex-col  mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Data Kerja Sama
    </h2>

    <div class="mt-5">
        <p class="text-lg font-semibold">Alur Kerja Sama</p>
        <ul>
            <li>- Owner dipersilahkan untuk melakukan download template kerja sama</li>
            <li>- Lakukan perjanjian bertemu dengan menggunakan fitur chat atau bisa request waktu secara langsung</li>
            <li>- Template kerja sama yang sudah diisi dan ditandatangani owner dibawa saat pertemuan</li>
            <li>- Jika terjadi kesepakatan, berikan surat kepada supplier.</li>
            <li>- Supplier menandatangani surat dan melakukan upload surat final di sistem</li>
        </ul>
    </div>
</div>
<!-- BEGIN: Chat Modal --->
<!-- END: Chat Modal --->

<!-- BEGIN: Jadwal Modal --->
<div class="modal" id="jadwal-modal">
    <div class="modal__content relative">
        <a data-dismiss="modal" href="javascript:;" class="absolute right-0 top-0 mt-3 mr-3"> <i data-feather="x" class="w-8 h-8 text-gray-500" onclick="closeModal()"></i> </a>
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">Kesepakatan Jadwal Bertemu</h2>
        </div>
        <div class="loading flex justify-center mt-5 mb-5 gap-5">
            <i data-loading-icon="puff" class="w-10 h-10 justify-center"></i> 
        </div>
        <div class="p-5" id="vertical-form">
            <div class="preview">
                <form method="POST">
                    @csrf
                    <div class="schedule_time">
                        {{-- diisi jquery --}}
                    </div>
                    <div class="status mt-3">
                        <label>Status</label>
                        <select  data-hide-search="true" name="schedule_accepted" class="select2 w-full">
                            <option value="Ditolak">Ditolak</option>
                            <option value="Disetujui">Disetujui</option>
                        </select>
                    </div>
                    <div class="status-final mt-3">
                        {{-- diisi jquery --}}
                    </div>
                    <button type="submit" class="button bg-theme-1 text-white mt-5">Simpan</button>
                </form>
            </div>
        </div>

        <div class="p-5 hidden" id="schedule">
            <div class="preview">
                <div>
                    <label>Waktu</label>
                    <p class="meet_schedule"></p>
                </div>
                <div class="mt-3">
                    <label>Status</label>
                    <p class="schedule_accepted"></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Jadwal Modal --->

<!-- BEGIN: Kesepakatan Modal --->
<div class="modal" id="kesepakatan-modal">
    <div class="modal__content relative">
        <a data-dismiss="modal" href="javascript:;" class="absolute right-0 top-0 mt-3 mr-3"> <i data-feather="x" class="w-8 h-8 text-gray-500" onclick="closeModal()"></i> </a>
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">Kesepakatan</h2>
        </div>
        <div class="loading flex justify-center mt-5 mb-5 gap-5">
            <i data-loading-icon="puff" class="w-10 h-10 justify-center"></i> 
        </div>
        <div class="p-5" id="cooperate">
            <div class="preview">
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="surat flex flex-col">
                        <label>Upload Surat (Jika tidak terjadi kesepakatan, tidak perlu upload surat. Cukup mengubah status persetujuan)</label>
                        <input type="file" name="cooperate_letter" class="image-input input border mt-2 image w-full">
                    </div>
                    <div class="status mt-3 flex flex-col">
                        <label>Kesepakatan</label>
                        <select  data-hide-search="true" name="cooperate_accepted" class="select2 w-full">
                            <option value="Ditolak">Ditolak</option>
                            <option value="Disetujui">Disetujui</option>
                        </select>
                    </div>
                    <div class="status-final mt-3">
                        {{-- diisi jquery --}}
                    </div>
                    <button type="submit" class="button bg-theme-1 text-white mt-5">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Kesepakatan Modal --->

<!-- BEGIN: Datatable -->
<div class="intro-y datatable-wrapper box p-5 mt-5">
    <table class="table table-report table-report--bordered display datatable w-full">
        <thead>
            <tr>
                <th class="border-b-2 whitespace-no-wrap">NAMA</th>
                <th class="border-b-2 text-center whitespace-no-wrap">AVATAR</th>
                <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
                <th class="border-b-2 text-center whitespace-no-wrap">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($owners as $owner)
                <tr>
                    <td class="border-b">
                        <div class="font-medium whitespace-no-wrap">{{ $owner->name }}</div>
                        <div class="text-gray-600 text-xs whitespace-no-wrap">{{ $owner->roles->first()->name }}</div>
                    </td>
                    <td class="text-center border-b">
                        <div class="flex sm:justify-center">
                            <div class="intro-x w-10 h-10 image-fit -ml-5">
                                <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="dist/images/preview-12.jpg">
                            </div>
                        </div>
                    </td>
                    <td class="text-center border-b">
                        @if (count($owner->Owners) == 0)
                            <p class="text-yellow-700"><strong>Belum bekerja sama</strong></p>
                        @else
                            @if ($owner->Owners->where("supplier_id", Auth::user()->id))
                                <p class="{{ $owner->Owners->where("supplier_id", Auth::user()->id)->first()->cooperate_accepted == "Menunggu" ? "text-yellow-700" : ($owner->Owners->where("supplier_id", Auth::user()->id)->first()->cooperate_accepted == "Disetujui" ? "text-green-500" : ($owner->Owners->where("supplier_id", Auth::user()->id)->first()->cooperate_accepted == "Ditolak" ? "text-red-700" : "")) }}">
                                    <strong>
                                        {{ $owner->Owners->where("supplier_id", Auth::user()->id)->first()->cooperate_accepted == "Menunggu" ? "Menunggu" : ($owner->Owners->where("supplier_id", Auth::user()->id)->first()->cooperate_accepted == "Disetujui" ? "Disetujui" : ($owner->Owners->where("supplier_id", Auth::user()->id)->first()->cooperate_accepted == "Ditolak" ? "Ditolak" : "")) }}
                                    </strong>
                                </p>
                            @endif
                        @endif
                    </td>
                    <td class="border-b w-5">
                        <div class="flex sm:justify-center items-center">
                            <a class="flex items-center mr-3 text-yellow-700" href="#"> <i data-feather="message-circle" class="w-4 h-4 mr-1"></i> Detail </a>
                            <a class="flex items-center mr-3 text-green-500" href="javascript:;" data-toggle="modal" data-target="#jadwal-modal" onclick="addCooperate({{ $owner->id }})"> <i data-feather="calendar" class="w-4 h-4 mr-1"></i> Jadwal </a>
                            <a class="flex items-center text-blue-700" href="javascript:;" data-toggle="modal" data-target="#kesepakatan-modal" onclick="showCooperate({{ $owner->id }})"> <i data-feather="archive" class="w-4 h-4 mr-1"></i> Kesepakatan </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- END: Datatable -->

@push('script')
    {{-- Alert Script --}}
    @if (session("success"))
        <script>
            // success alert
            Swal.fire(
                "Sukses",
                `{{ session("success") }}`,
                "success"
            );
        </script>
    @elseif(session("error"))
        <script>
            // error alert
            Swal.fire(
                "Gagal",
                `{{ session("error") }}`,
                "error"
            );
        </script>
    @elseif($errors->any())
        <script>
            // erro alert
            Swal.fire(
                "Gagal",
                `@foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach`,
                "error"
            );
        </script>
    @endif
    
    {{-- Additional Script --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script>
        // create cooperate data
        function addCooperate(id)
        {
            $.ajax({
                url: `{{ url("supplier/integrasi/owner") }}/${id}`,
                method: "GET",
                dataType: "json",
                beforeSend: function()
                {
                    // tampilkan loading dan sembunyikan form
                    $(".loading").removeClass("hidden").addClass("flex");
                    $("#jadwal-modal form").addClass("hidden");
                },
                success: function(data)
                {
                    $(".loading").removeClass("flex").addClass("hidden");
                    $("#jadwal-modal form").removeClass("hidden");
                    $("#jadwal-modal .schedule_time").html(`<label>Waktu Permintaan Owner</label><br>
                                                           <strong>${data.meet_schedule}</strong>`);
                    $("#jadwal-modal form").attr("action", `{{ url("supplier/integrasi/owner") }}/${id}`);
                    if(data.schedule_accepted == "Menunggu")
                    {
                        $("#jadwal-modal form .status-final").html(`<label>Status Final</label><br/><p class="text-yellow-700"><strong>${data.schedule_accepted}</strong></p>`)
                    } else if(data.schedule_accepted == "Disetujui")
                    {
                        $("#jadwal-modal form .status").addClass("hidden");
                        $("#jadwal-modal form button").addClass("hidden");
                        $("#jadwal-modal form .status-final").html(`<label>Status Final</label><br/><p class="text-green-500"><strong>${data.schedule_accepted}</strong></p>`)
                    } else if(data.schedule_accepted == "Ditolak")
                    {
                        $("#jadwal-modal form .status-final").html(`<label>Status Final</label><br/><p class="text-red-500"><strong>${data.schedule_accepted}</strong></p>`)
                    }
                }
            })
        }

        function showCooperate(id)
        {
            $.ajax({
                url: `{{ url("supplier/integrasi/owner") }}/${id}`,
                method: "GET",
                dataType: "json",
                beforeSend: function()
                {
                    // tampilkan loading dan sembunyikan form
                    $(".loading").removeClass("hidden").addClass("flex");
                    $("#kesepakatan-modal #cooperate").addClass("hidden");
                },
                success: function(data)
                {
                    $(".loading").removeClass("flex").addClass("hidden");
                    $("#kesepakatan-modal #cooperate").removeClass("hidden");
                    if(!data.schedule_accepted)
                    {
                        $("#kesepakatan-modal button").addClass("hidden");
                        $("#kesepakatan-modal .surat").html("<label>Surat Perjanjian</label><p>Belum ada surat</p>");
                        $("#kesepakatan-modal .status-final").html(`<label>Status</label><p class="text-yellow-700"><strong>Belum bekerja sama</strong></p>`)
                    }
                    else
                    {
                        if(data.cooperate_accepted == "Menunggu")
                        {
                            $("#kesepakatan-modal .status-final").html(`<label>Status</label><p class="text-yellow-700"><strong>${data.cooperate_accepted}</strong></p>`)
                            $("#kesepakatan-modal form").attr("action", `{{ url("supplier/integrasi/owner/final") }}/${id}`)
                        }else if(data.cooperate_accepted == "Ditolak")
                        {
                            $("#kesepakatan-modal button").addClass("hidden");
                            $("#kesepakatan-modal .status").addClass("hidden");
                            $("#kesepakatan-modal .surat").html("<label>Surat Perjanjian</label><p>Belum ada surat</p>");
                            $("#kesepakatan-modal .status-final").html(`<label>Status</label><p class="text-red-700"><strong>${data.cooperate_accepted}</strong></p>`)
                        }else if(data.cooperate_accepted == "Disetujui")
                        {
                            $("#kesepakatan-modal button").addClass("hidden");
                            $("#kesepakatan-modal .status").addClass("hidden");
                            $("#kesepakatan-modal .surat").html(`<label>Surat Perjanjian</label><a href="${data.media[0].original_url}"><button type="button" class="button w-24 mr-1 mb-2 bg-theme-9 text-white">Dokumen</button></a>`);
                            $("#kesepakatan-modal .status-final").html(`<label>Status</label><p class="text-green-500"><strong>${data.cooperate_accepted}</strong></p>`)
                        } 
                    }
                }
            })
        }
        function closeModal()
        {
            $("#jadwal-modal #schedule").addClass("hidden");
        }
    </script>
@endpush
@endsection