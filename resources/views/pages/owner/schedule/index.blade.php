@extends('layouts.owner.app')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Data Penjadwalan Hewan Ternak
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <!-- BEGIN: Add Modal Button --->
        <a href="javascript:;" data-toggle="modal" data-target="#jadwal-modal" class="button text-white bg-theme-1 shadow-md mr-2">+ Tambah Penjadwalan</a>
        <!-- END: Add Modal Button --->
        <!-- BEGIN: Add Modal Jadwal --->
        <div class="modal" id="jadwal-modal">
            <div class="modal__content relative"> 
                <a data-dismiss="modal" href="javascript:;" class="absolute right-0 top-0 mt-3 mr-3"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Data Penjadwalan
                    </h2>
                </div>
                <div class="loading justify-center mt-5 mb-3 gap-5 hidden">
                    <i data-loading-icon="puff" class="w-10 h-10 justify-center"></i> 
                </div>
                <div class="p-5" id="vertical-form">
                    <div class="preview">
                        <form action="{{ route("owner.jadwal.store") }}" method="POST">
                            @csrf
                            <div> 
                                <label>Nama Ternak</label>
                                <input name="name" type="text" class="input w-full border mt-2" placeholder="Masukkan nama ternak">
                            </div>

                            <div class="mt-3">
                                <label>Jadwal <span class="text-gray-500">(Jika tidak perlu jam, boleh kosong)</span> </label>
                                <div class="flex gap-2 mt-2">
                                    <input type="text" class="input border h-10 w-[200px]" placeholder="Masukkan nama jadwal" name="schedule_name[]">
                                    <input type="time" class="input border h-10" name="schedule_time[]">
                                    <select data-hide-search="true" class="select2 w-[500px]" name="schedule_type[]">
                                        <option value="Daily">Harian</option>
                                        <option value="Weekly">Mingguan</option>
                                        <option value="Monthly">Bulanan</option>
                                        <option value="Yearly">Tahunan</option>
                                    </select>
                                    <button type="button" class="button inline-block mr-1 mb-2 border border-theme-1 text-theme-1" onclick="addInputElement()">+</button>
                                </div>
                                <div class="input-container"></div>
                            </div>
                            <button type="submit" class="button bg-theme-1 text-white mt-5">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Begin Modal Jadwal--->

        <!-- BEGIN: Lihat Jadwal Modal --->
        <div class="modal" id="lihat-jadwal-modal">
            <div class="modal__content relative"> 
                <a data-dismiss="modal" href="javascript:;" class="absolute right-0 top-0 mt-3 mr-3"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Detail Jadwal
                    </h2>
                </div>
                <div class="loading justify-center mt-5 mb-3 gap-5 hidden">
                    <i data-loading-icon="puff" class="w-10 h-10 justify-center"></i> 
                </div>
                <div class="p-5" id="schedule">
                    <div class="preview">
                        <ul>
                            <li class="flex gap-3">
                                <i data-feather="circle" class="w-2 mr-1"></i> <p class="my-auto">Makan Pagi : 17.00 WIB</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Lihat Jadwal Modal --->

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
                <th class="border-b-2 whitespace-no-wrap">NAMA TERNAK</th>
                <th class="border-b-2 text-center whitespace-no-wrap">JADWAL</th>
                <th class="border-b-2 text-center whitespace-no-wrap">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($animals as $animal)
                <tr>
                    <td class="border-b">
                        <div class="font-medium whitespace-no-wrap">{{ $animal->Animal->name }}</div>
                    </td>
                    <td class="text-center border-b">
                        <a href="javascript:;" data-toggle="modal" data-target="#lihat-jadwal-modal" onclick="showSchedule({{ $animal->Animal->id }})"><button class="button w-24 mr-1 mb-2 bg-theme-1 text-white">Lihat</button></a>
                    </td>
                    <td class="border-b w-5">
                        <div class="flex sm:justify-center items-center">
                            <a class="edit-jadwal flex items-center mr-3 text-yellow-700" href="javascript:;" data-toggle="modal" data-target="#jadwal-modal" onclick="editSchedule({{ $animal->Animal->id }})"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit Data</a>

                            <form action="{{ route("owner.jadwal.destroy", $animal->id) }}" method="post" id="hapus">
                                @csrf
                                @method("DELETE")
                                <button type="button" class="hapus-jadwal btn bg-base flex items-center mr-3 text-red-700"> <i data-feather="trash" class="w-4 h-4 mr-1"></i> Hapus Data</button>
                            </form>
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
                `Data tidak valid`,
                "error"
            );
        </script>
    @elseif($errors->any())
        <script>
            // erro alert
            Swal.fire(
                "Gagal",
                `Data tidak valid`,
                "error"
            );
        </script>
    @endif
    
    {{-- Additional Script --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script>
        $("button.hapus-jadwal").on("click", function()
        {
            Swal.fire({
                title: 'Apakah anda yakin untuk menghapus data jadwal ini?',
                showDenyButton: true,
                confirmButtonText: 'Hapus',
                denyButtonText: `Batal`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $("form#hapus").submit();
                } else if (result.isDenied) {
                    Swal.fire('Batal', 'Data jadwal batal dihapus', 'info')
                }
            });
        });
        function showSchedule(id)
        {
            $.ajax({
                url: `{{ url("owner/jadwal") }}/${id}/show`,
                method: 'GET',
                dataType: 'json',
                beforeSend: function()
                {
                    // tampilkan loading dan sembunyikan form
                    $(".loading").removeClass("hidden").addClass("flex");
                    $("#schedule").addClass("hidden");
                },
                success: function (data) {
                    $(".loading").removeClass("flex").addClass("hidden");
                    $("#schedule").removeClass("hidden");
                    
                    const scheduleList = $("#schedule ul");
                    scheduleList.empty(); // Kosongkan daftar jadwal sebelum memperbarui
                    
                    data.forEach(item => {
                        const listItem = $("<li>").addClass("flex gap-3");
                        const circleIcon = $("<i>").attr("data-feather", "circle").addClass("w-2 mr-1");
                        const scheduleText = $("<p>").addClass("my-auto font-semibold").text(`${item.schedule_name} : ${item.schedule_time} WIB`);

                        listItem.append(circleIcon, scheduleText);
                        scheduleList.append(listItem);
                    });

                    feather.replace(); // Refresh ikon menggunakan Feather Icons
                }
            })
        }

        function editSchedule(id)
        {
            $.ajax({
                url: `{{ url("owner/jadwal") }}/${id}/show`,
                method: 'GET',
                dataType: 'json',
                beforeSend: function()
                {
                    // tampilkan loading dan sembunyikan form
                    $(".loading").removeClass("hidden").addClass("flex");
                    $("#jadwal-modal form").addClass("hidden");
                },
                success: function(data) {
                    $(".loading").removeClass("flex").addClass("hidden");
                    $("#jadwal-modal form").removeClass("hidden");

                    // Isi data ke input dengan nama "name"
                    $("#jadwal-modal form input[name='name']").val(data[0].animal_owner.animal.name);
                    $("#jadwal-modal form").attr("action", `{{ url("owner/jadwal") }}/${id}/update`);
                    $("#jadwal-modal form").append(`@method("PUT")`);
                }
            })
        }

        function addInputElement() {
            const inputContainer = document.querySelector('.input-container');
            const newInput = document.createElement('div');
            newInput.className="flex gap-2";
            newInput.innerHTML = `<input type="text" class="input border h-10 w-[200px]" placeholder="Masukkan nama jadwal" name="schedule_name[]">
                                    <input type="time" class="input border h-10" name="schedule_time[]">
                                    <select data-hide-search="true" class="select2 w-[500px]" name="schedule_type[]">
                                        <option value="Daily">Harian</option>
                                        <option value="Weekly">Mingguan</option>
                                        <option value="Monthly">Bulanan</option>
                                        <option value="Yearly">Tahunan</option>
                                    </select>
                                    <button type="button" class="button inline-block mr-1 mb-2 border border-theme-6 text-theme-6" onclick="removeInputElement(this)">-</button>`;
            window.requestAnimationFrame(() => {
                // panggil fungsi Select2 pada elemen select yang baru saja ditambahkan
                $('.select2').select2();
            });
            inputContainer.appendChild(newInput);
        }

        function removeInputElement(button) {
            const parent = button.parentElement.parentElement;
            parent.removeChild(button.parentElement);
        }
    </script>
@endpush
@endsection