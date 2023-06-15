@extends('layouts.owner.app')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Update Profile
    </h2>
</div>
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
        <!-- BEGIN: Display Information -->
        <div class="intro-y box lg:mt-5">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    Display Information
                </h2>
            </div>
            <div class="p-5">
                <form action="{{ route("owner.profile.update", Auth::user()->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="grid grid-cols-12 gap-5">
                        <div class="col-span-12 xl:col-span-4">
                            <div class="border border-gray-200 rounded-md p-5">
                                <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                    <img class="image-preview rounded-md" alt="Midone Tailwind HTML Admin Template" src="{{ Auth::user()->getFirstMediaUrl("avatar") }}">
                                </div>
                                <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                    <button type="button" class="button w-full bg-theme-1 text-white">Ubah Foto</button>
                                    <input type="file" class="image-input w-full h-full top-0 left-0 absolute opacity-0" onchange="previewImage()" name="avatar">
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 xl:col-span-8">
                            <div>
                                <label>Nama</label>
                                <input type="text" class="input w-full border bg-gray-100 mt-2" value="{{ Auth::user()->name }}" name="name">
                            </div>
                            <div class="mt-3">
                                <label>E-mail</label>
                                <input type="email" class="input w-full border bg-gray-100 mt-2" value="{{ Auth::user()->email }}" name="email">
                            </div>
                            <div class="mt-3">
                                <label>Password Baru</label>
                                <input type="password" class="input w-full border bg-gray-100 mt-2" name="password">
                            </div>
                            <div class="mt-3">
                                <label>Nama Usaha</label>
                                <input type="text" class="input w-full border bg-gray-100 mt-2" value="{{ Auth::user()->Business->name }}" name="business_name">
                            </div>
                            <div class="mt-3">
                                <label>Kontak</label>
                                <input type="number" class="input w-full border bg-gray-100 mt-2" value="{{ Auth::user()->Business->contact }}" name="contact">
                            </div>
                            <div class="mt-3">
                                <label>Alamat</label>
                                <input type="text" class="input w-full border bg-gray-100 mt-2" value="{{ Auth::user()->Business->address }}" name="address">
                            </div>
                            <div class="mt-3">
                                <label>Deskripsi</label>
                                <textarea data-feature="basic" class="summernote" name="description" name="description">{{ Auth::user()->Business->description }}</textarea>
                            </div>
                            <button type="submit" class="button w-20 bg-theme-1 text-white mt-3">Ubah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Display Information -->
    </div>
</div>
@endsection
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
        `session("error")`,
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
<script>
    function previewImage()
        {
            const image_input = document.querySelector(".image-input");
            const image_preview = document.querySelector(".image-preview");
            
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image_input.files[0]);

            oFReader.onload = function(oFREvent) {
                image_preview.src = oFREvent.target.result;
            }
        }
</script>
@endpush