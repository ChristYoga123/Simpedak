@extends('layouts.owner.app')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Data Bahan Baku
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <!-- BEGIN: Add Modal Button --->
        <a href="javascript:;" data-toggle="modal" data-target="#raw-product-data-modal" class="button text-white bg-theme-1 shadow-md mr-2">+ Tambah Bahan Baku</a>
        <!-- END: Add Modal Button --->
        <!-- BEGIN: Add Modal Raw Product --->
        <div class="modal" id="raw-product-data-modal">
            <div class="modal__content relative"> 
                <a data-dismiss="modal" href="javascript:;" class="absolute right-0 top-0 mt-3 mr-3" onclick="closeRawProductData()"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Data Bahan Baku
                    </h2>
                </div>
                <div class="loading justify-center mt-5 mb-3 gap-5 hidden">
                    <i data-loading-icon="puff" class="w-10 h-10 justify-center"></i> 
                </div>
                <div class="p-5" id="vertical-form">
                    <div class="preview">
                        <form action="{{ route("owner.product.raw-product.store") }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div> 
                                <label>Nama</label>
                                <input name="name" type="text" class="input w-full border mt-2" placeholder="Masukkan nama bahan baku" required>
                            </div>
                            <div class="mt-3">
                                <label>Satuan</label> 
                                <select name="unit" class="select2 w-full" id="select2">
                                    <option value="">--- Pilih satuan bahan baku ---</option>
                                    <option value="Package">Package</option>
                                    <option value="Piece">Piece</option>
                                    <option value="Kilogram">Kilogram</option>
                                    <option value="Liter">Liter</option>
                                    <option value="Ton">Ton</option>
                                    <option value="Buah">Buah</option>
                                    <option value="Ekor">Ekor</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label>Gambar</label>
                                <img class="image-preview" width="300px">
                                <input name="image" type="file" class="image-input input w-full border mt-2" accept="image/*" required onchange="previewImage()">
                            </div>
                            <button type="submit" class="button bg-theme-1 text-white mt-5">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Begin Modal Raw Product--->

        <!-- BEGIN: Add Modal Quantity Raw Product --->
        <div class="modal" id="raw-product-quantity-modal">
            <div class="modal__content relative"> 
                <a data-dismiss="modal" href="javascript:;" class="absolute right-0 top-0 mt-3 mr-3"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Data Stok Bahan Baku
                    </h2>
                </div>
                <div class="loading justify-center mt-5 mb-5 gap-5 hidden">
                    <i data-loading-icon="puff" class="w-10 h-10 justify-center"></i> 
                </div>
                <form>
                    @csrf
                    <div class="p-5" id="vertical-form">
                        <div class="preview">
                            <div>
                                <label>Jumlah penambahan stok</label><br>
                                <strong><i>(Jika terjadi kesalahan input yang menyebabkan stok menjadi lebih dari yang dimiliki, maka bisa dikurangi dengan memberi tanda (-) sebelum angka)</i></strong>
                                <input type="number" class="input w-full border mt-2" name="quantity" onkeyup="addRawProductStock()" value="0" required>
                            </div>
                            <div class="mt-3">
                                <p>Stok sekarang: <strong><span id="unit-sekarang"></span> + <span id="unit-tambah">Nilai Tambah</span> = <span id="unit-hasil">Hasil Tambah</span></strong></p>
                            </div>
                            <div class="mt-3">
                                <label>Deskripsi</label>
                                <textarea data-feature="basic" class="summernote" name="history" required></textarea>
                            </div>
                            <button type="submit" class="button bg-theme-1 text-white mt-5">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Begin Modal Quantity Raw Product--->

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
                <th class="border-b-2 whitespace-no-wrap">NAMA</th>
                <th class="border-b-2 text-center whitespace-no-wrap">GAMBAR</th>
                <th class="border-b-2 text-center whitespace-no-wrap">JUMLAH</th>
                <th class="border-b-2 text-center whitespace-no-wrap">SATUAN</th>
                <th class="border-b-2 text-center whitespace-no-wrap">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product_owners as $product_owner)
                <tr>
                    <td class="border-b">
                        <div class="font-medium whitespace-no-wrap">{{ $product_owner->Product->name }}</div>
                        <div class="text-gray-600 text-xs whitespace-no-wrap">{{ $product_owner->ProductType->type }}</div>
                    </td>
                    <td class="text-center border-b">
                        <div class="flex sm:justify-center">
                            <div class="intro-x w-10 h-10 image-fit"></div>
                            <div class="intro-x w-10 h-10 image-fit -ml-5">
                                <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="{{ $product_owner->getFirstMediaUrl("product-image") }}">
                            </div>
                            <div class="intro-x w-10 h-10 image-fit -ml-5"></div>
                        </div>
                    </td>
                    <td class="text-center border-b">{{ $product_owner->quantity }}</td>
                    <td class="w-40 border-b">
                        <div class="flex items-center sm:justify-center"><strong>{{ $product_owner->unit }}</strong></div>
                    </td>
                    <td class="border-b w-5">
                        <div class="flex sm:justify-center items-center">
                            <a href="{{ route("owner.product.raw-product.history", $product_owner->Product->slug) }}" class="flex items-center mr-3"> <i data-feather="book-open" class="w-4 h-4 mr-1"></i> Riwayat </a>
                            <a onclick="editRawProductData({{ $product_owner->id }})" class="edit-raw-product-data flex items-center mr-3 text-yellow-700" href="javascript:;" data-toggle="modal" data-target="#raw-product-data-modal"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit Data</a>
                            <a onclick="editRawProductQuantity({{ $product_owner->id }})" href="javascript:;" data-toggle="modal" data-target="#raw-product-quantity-modal" class="flex items-center mr-3 text-green-500" > <i data-feather="check-square" class="w-4 h-4 mr-1" ></i> Edit Stok</a>
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
                `
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                `,
                "error"
            );
        </script>
    @endif
    
    {{-- Additional Script --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script>
        // preview image
        function previewImage()
        {
            const image_input = document.querySelector(".image-input");
            const image_preview = document.querySelector(".image-preview");
            
            image_preview.style.display = "block";

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image_input.files[0]);

            oFReader.onload = function(oFREvent) {
                image_preview.src = oFREvent.target.result;
            }
        }

        // edit raw product data modal
        function editRawProductData(id)
        {
            $.ajax({
                url: `{{ url("owner/produk/bahan-baku") }}/${id}/edit`,
                method: 'GET',
                dataType: 'json',
                beforeSend: function()
                {
                    // tampilkan loading dan sembunyikan form
                    $(".loading").removeClass("hidden").addClass("flex");
                    $("#raw-product-data-modal form").addClass("hidden");
                },
                success: function(data)
                {
                    // Sembunyikan loading dan tampilkan form
                    $(".loading").removeClass("flex").addClass("hidden");
                    $("#raw-product-data-modal form").removeClass("hidden");
                    // Menampilkan data pada field modal
                    $('#raw-product-data-modal input[name="name"]').val(data.name);
                    $('#raw-product-data-modal select[name="unit"]').val(data.unit).trigger('change');
                    $('#raw-product-data-modal .image-preview').attr('src', data.image);
                    // Merubah attribut pada form
                    $("#raw-product-data-modal form").attr("action", `{{ url("owner/produk/bahan-baku") }}/${id}/update`);
                    $("#raw-product-data-modal form").attr("method", "POST");
                    $("#raw-product-data-modal form").append(`@method("PUT")`);
                    $("#raw-product-data-modal input[type='file']").removeAttr(`required`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Menampilkan pesan error jika Ajax gagal
                    alert('Terjadi kesalahan saat mengambil data!');
                }
            })
        }
        function addRawProductStock()
        {
            $("#raw-product-quantity-modal span#unit-tambah").html($("#raw-product-quantity-modal input[name='quantity']").val());
            $("#raw-product-quantity-modal span#unit-hasil").html(parseInt($("#raw-product-quantity-modal span#unit-sekarang").html()) + parseInt($("#raw-product-quantity-modal input[name='quantity']").val()));
        }

        function editRawProductQuantity(id)
        {
            $.ajax({
                url: `{{ url("owner/produk/bahan-baku") }}/${id}/edit`,
                method: 'GET',
                dataType: 'json',
                beforeSend: function()
                {
                    // tampilkan loading dan sembunyikan form
                    $(".loading").removeClass("hidden").addClass("flex");
                    $("#raw-product-quantity-modal form").addClass("hidden");
                },
                success: function(data)
                {
                    // sembunyikan loading dan tampilkan form
                    $(".loading").removeClass("flex").addClass("hidden");
                    // tampilkan form sembunyikan loading
                    $("#raw-product-quantity-modal form").removeClass("hidden");
                    $("#raw-product-quantity-modal span#unit-sekarang").html(data.quantity);
                    // merubah attribute form
                    $("#raw-product-quantity-modal form").attr("action", `{{ url("owner/produk/bahan-baku/kuantitas") }}/${id}/update`);
                    $("#raw-product-quantity-modal form").attr("method", "POST");
                    $("#raw-product-quantity-modal form").append(`@method("PUT")`);
                }
            })
        }



        function closeRawProductData()
        {
            // Membersihkan value dari semua form
            $('#vertical-form input[name="name"]').val("");
            $('#vertical-form select[name="unit"]').val($('#raw-product-data-modal #vertical-form select[name="unit"] option:first').val()).trigger('change');
            // Hapus gambar preview jika ada
            $('.image-preview').attr('src', '');
            // Hapus pesan error
            $(".border-red-500").removeClass("border-red-500"); 
            $('.text-red-500').text('');
        }
    </script>
@endpush
@endsection