@push('style')
    <link rel="stylesheet" href="{{ asset("assets/css/app.css") }}">
@endpush
@extends('layouts.home.app')

@section('content')
<div class="intro-y box py-10 sm:py-20 mt-5">
    <div class="wizard flex flex-col lg:flex-row justify-center px-5 sm:px-20">
        <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
            <button class="w-10 h-10 rounded-full button text-white bg-theme-1" id="menu-1">1</button>
            <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Detail Personal</div>
        </div>
        <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
            <button class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200" id="menu-2">2</button>
            <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-gray-700">Detail Usaha</div>
        </div>
        <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
            <button class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200" id="menu-3">3</button>
            <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-gray-700">Payment</div>
        </div>
        <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 absolute mt-5"></div>
    </div>

    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-base-300">
        <div class="lg:py-5 p-3 lg:px-10 w-full rounded-xl border border-base-200">
            <div class="font-medium text-base">Sudah memiliki akun? <strong>Masuk</strong></div>
            <div class="flex gap-5 flex-wrap mt-3">
                <div class="flex flex-col gap-3">
                    <label class="font-md">Alamat Email</label>
                    <input type="text" class="input w-[23.5rem] lg:w-96 max-w-lg border border-base-200">
                </div>
                <div class="flex flex-col gap-3">
                    <label class="font-md">Password</label>
                    <input type="text" class="input w-[23.5rem] lg:w-96 max-w-lg border border-base-200">
                </div>
                <div class="flex items-end gap-3">
                    <button class="btn w-[23.5rem] flex bg-[#3A00E5] lg:w-96 border-0">Masuk</button>
                </div>
            </div>
        </div>

        <form action="{{ route("home.register") }}" method="post" enctype="multipart/form-data">
            @csrf
            <div id="form-1">
                <select name="role" class="w-full input select2 my-3 border border-base-200 lg:max-w-sm">
                    <option value="">Pilih Role</option>
                    <option value="Owner">Owner</option>
                    <option value="Supplier">Supplier</option>
                </select>
                <div class="font-medium text-base">Detail Personal</div>
                <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Nama Pemilik Usaha</div>
                        <input type="text" name="name" class="input w-full border border-base-300 flex-1" ">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Nomor Telepon</div>
                        <input type="number" name="contact" class="input w-full border border-base-300 flex-1">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Alamat</div>
                        <input type="text" name="address" class="input w-full border border-base-300 flex-1">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Foto Profil</div>
                        <input type="file" name="avatar" class="input w-full border border-base-300 flex-1">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Alamat E-mail</div>
                        <input type="email" name="email" class="input w-full border border-base-300 flex-1">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Password</div>
                        <input type="password" name="password" class="input w-full border border-base-300 flex-1">
                    </div>
                    <div class="intro-y col-span-12 flex items-center justify-center sm:justify-end mt-5">
                        <a href="{{ route("home.feature") }}"><button type="button" class="button w-28 justify-center block bg-gray-200 text-gray-600">Sebelumnya</button></a>
                        <button type="button" class="button w-28 justify-center block bg-theme-1 text-white ml-2" id="next">Selanjutnya</button>
                    </div>
                </div>
            </div>

            <div id="form-2" class="hidden">
                <div class="font-medium text-base">Detail Usaha</div>
                <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Nama Usaha</div>
                        <input type="text" name="business_name" class="input w-full border border-base-300 flex-1">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Galeri Usaha</div>
                        <input type="file" name="business_galleries[]" class="input w-full border border-base-300 flex-1" multiple>
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Deskripsi Usaha</div>
                        <input type="text" name="description" class="input w-full border border-base-300 flex-1">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Surat Izin</div>
                        <input type="file" name="business_permission_letter" class="input w-full border border-base-300 flex-1">
                    </div>
                    <div class="intro-y col-span-12 flex items-center justify-center sm:justify-end mt-5">
                        <button type="button" class="button w-28 justify-center block bg-gray-200 text-gray-600" id="previous">Sebelumnya</button>
                        <button type="submit" class="button w-28 justify-center block bg-theme-1 text-white ml-2">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@push('script')
    @if (session("error"))
        <script>
            Swal.fire(
                "Gagal",
                `{{ session("error") }}`,
                "error"
            );
        </script>
    @elseif ($errors->any())
        <script>
            Swal.fire(
                "Gagal",
                `@foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach`,
                "error"
            );
        </script>
    @endif
    <script>
        let idx = 1;
        $("button#next").on("click", () => {
            $("#menu-2").removeClass("text-gray-600");
            $("#menu-2").addClass("bg-theme-1 text-white");
            $("#form-1").addClass("hidden");
            $("#form-2").removeClass("hidden");
        });

        $("button#previous").on("click", () => {
            $("#menu-2").removeClass("bg-theme-1 text-white");
            $("#menu-2").addClass("text-gray-600");
            $("#form-1").removeClass("hidden");
            $("#form-2").addClass("hidden");
        });
    </script>
@endpush
@endsection