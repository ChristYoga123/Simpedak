@extends('layouts.owner.app')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Data Produk Jadi
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <!-- BEGIN: Add Modal Button --->
        <a href="javascript:;" data-toggle="modal" data-target="#serve-product-data-modal" class="button text-white bg-theme-1 shadow-md mr-2">+ Tambah Produk Jadi</a>
        <!-- END: Add Modal Button --->
        <!-- BEGIN: Add Modal Serve Product --->
        <div class="modal" id="serve-product-data-modal">
            <div class="modal__content relative"> 
                <a data-dismiss="modal" href="javascript:;" class="absolute right-0 top-0 mt-3 mr-3"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Data Produk Jadi
                    </h2>
                </div>
                <div class="loading justify-center mt-5 mb-3 gap-5 hidden">
                    <i data-loading-icon="puff" class="w-10 h-10 justify-center"></i> 
                </div>
                <div class="p-5" id="vertical-form">
                    <div class="preview">
                        <form action="{{ route("owner.product.serve-product.store") }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div> 
                                <label>Nama</label>
                                <input name="name" type="text" class="input w-full border mt-2" placeholder="Masukkan nama produk jadi" required>
                            </div>
                            <div class="mt-3">
                                <label>Satuan</label> 
                                <select name="unit" class="select2 w-full" id="select2">
                                    <option value="">--- Pilih satuan produk jadi ---</option>
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
                                <label>Harga</label>
                                <div class="relative mt-2">
                                    <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600">Rp</div> 
                                        <input type="number" class="input px-12 w-full border col-span-4" placeholder="Masukkan harga produk jadi" name="price">
                                    <div class="absolute top-0 right-0 rounded-r w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600">.00</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label>Resep <span class="text-gray-500">(Jika tidak perlu resep, boleh dikosongi)</span> </label>
                                <div class="flex gap-2 mt-2">
                                    <select data-hide-search="true" class="select2 w-full @error("recipe")
                                            border-red-500
                                        @enderror" name="recipe[]">
                                        <option value="">---Pilih Resep---</option>
                                        @foreach ($raw_product_owners as $item)
                                            <option value="{{ $item->id }}">{{ $item->Product->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" step="any" class="input border h-10" placeholder="Masukkan jumlah bahan jadi" name=" quantity[]">
                                    <button type="button" class="button inline-block mr-1 mb-2 border border-theme-1 text-theme-1" onclick="addInputElement()">+</button>
                                </div>
                                <div class="input-container"></div>
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
        <!-- END: Begin Modal Serve Product--->

        <!-- BEGIN: Add Modal Quantity Serve Product --->
        <div class="modal" id="serve-product-quantity-modal">
            <div class="modal__content relative"> 
                <a data-dismiss="modal" href="javascript:;" class="absolute right-0 top-0 mt-3 mr-3"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Data Stok Produk Jadi
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
                                <label>Jumlah penambahan stok</label>
                                <input type="number" class="input w-full border mt-2" name="quantity" onkeyup="addRawProductStock()" value="0" required>
                            </div>
                            <div class="mt-3">
                                <p>Stok sekarang: <strong><span id="unit-sekarang"></span> + <span id="unit-tambah">Nilai Tambah</span> = <span id="unit-hasil">Hasil Tambah</span></strong></p>
                            </div>
                            <div class="mt-3 recipes">
                                <h1>Kalkulasi pemakaian bahan baku:</h1>
                                <ul id="recipe">
                                </ul>
                            </div>
                            
                            <button type="submit" class="button bg-theme-1 text-white mt-5">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Modal Quantity Serve Product--->

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
                <th class="border-b-2 text-center whitespace-no-wrap">RESEP</th>
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
                    <td class="w-40 text-center">
                        <ul>
                            @forelse ($product_owner->Recipes as $recipe)
                                <li>{{ $recipe->RawProduct->Product->name }} {{ $recipe->quantity }} {{ $recipe->RawProduct->unit }}</li>
                            @empty
                                <li>Tidak ada resep</li>
                            @endforelse
                        </ul>
                    </td>
                    <td class="text-center border-b">{{ $product_owner->quantity }}</td>
                    <td class="w-20 border-b">
                        <div class="flex items-center sm:justify-center"><strong>{{ $product_owner->unit }}</strong></div>
                    </td>
                    <td class="border-b w-5">
                        <div class="flex sm:justify-center items-center">
                            <a onclick="editServeProductData({{ $product_owner->id }})" class="edit-serve-product-data flex items-center mr-3 text-yellow-700" href="javascript:;" data-toggle="modal" data-target="#serve-product-data-modal"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit Data</a>
                            <a onclick="editServeProductQuantity({{ $product_owner->id }})" href="javascript:;" data-toggle="modal" data-target="#serve-product-quantity-modal" class="flex items-center mr-3 text-green-500" > <i data-feather="check-square" class="w-4 h-4 mr-1" ></i> Edit Stok</a>
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

        function editServeProductData(id)
        {
            $.ajax({
                url: `{{ url("owner/produk/produk-jadi") }}/${id}/show`,
                method: 'GET',
                dataType: 'json',
                beforeSend: function()
                {
                    // tampilkan loading dan sembunyikan form
                    $(".loading").removeClass("hidden").addClass("flex");
                    $("#serve-product-data-modal form").addClass("hidden");
                },
                success: function(data)
                {
                    // sembunyikan loading dan tampilkan form
                    $(".loading").removeClass("flex").addClass("hidden");
                    $("#serve-product-data-modal form").removeClass("hidden");
                    // isi form
                    $("#serve-product-data-modal form").attr("action", `{{ url("owner/produk/produk-jadi") }}/${id}/update`);
                    $("#serve-product-data-modal form").append(`@method("PUT")`);
                    $('#serve-product-data-modal input[name=name]').val(data.name);
                    $('#serve-product-data-modal select[name=unit]').val(data.unit).trigger("change");
                    $('#serve-product-data-modal input[name=price]').val(data.price);
                    $('#serve-product-data-modal input[type=file]').removeAttr("required");
                    $('#serve-product-data-modal .image-preview').attr("src", data.image);
                }
            });
        }

        function editServeProductQuantity(id)
        {
            $.ajax({
                url: `{{ url("owner/produk/produk-jadi") }}/${id}/show`,
                method: 'GET',
                dataType: 'json',
                beforeSend: function()
                {
                    // tampilkan loading dan sembunyikan form
                    $(".loading").removeClass("hidden").addClass("flex");
                    $("#serve-product-quantity-modal form").addClass("hidden");
                },
                success: function(data)
                {
                    // sembunyikan loading dan tampilkan form
                    $(".loading").removeClass("flex").addClass("hidden");
                    // tampilkan form sembunyikan loading
                    $("#serve-product-quantity-modal form").removeClass("hidden");
                    $("#serve-product-quantity-modal span#unit-sekarang").html(data.quantity);
                    // merubah attribute form
                    $("#serve-product-quantity-modal form").attr("action", `{{ url("owner/produk/produk-jadi/kuantitas") }}/${id}/update`);
                    $("#serve-product-quantity-modal form").attr("method", "POST");
                    $("#serve-product-quantity-modal form").append(`@method("PUT")`);
                    // resep
                    const recipes = data.recipes;
                    const ul = $("#serve-product-quantity-modal ul#recipe");
                    if(recipes.length > 0)
                    {
                        ul.empty();
                        recipes.forEach(recipe => {
                            const li = $("<li>").text(recipe.raw_product.product.name + " : " + recipe.quantity);
                            ul.append(li);
                        });
                    } else {
                        ul.empty();
                        const li = $("<li>").text(`Tidak ada resep`);
                        ul.append(li);
                    }
                }
            })
        }

        function addRawProductStock()
        {
            $("#serve-product-quantity-modal span#unit-tambah").html($("#serve-product-quantity-modal input[name='quantity']").val());
            $("#serve-product-quantity-modal span#unit-hasil").html(parseInt($("#serve-product-quantity-modal span#unit-sekarang").html()) + parseInt($("#serve-product-quantity-modal input[name='quantity']").val()));
        }
        // edit raw product data modal
        // function editRawProductData(id)
        // {
        //     $.ajax({
        //         url: `{{ url("owner/produk/bahan-baku") }}/${id}/edit`,
        //         method: 'GET',
        //         dataType: 'json',
        //         beforeSend: function()
        //         {
        //             // tampilkan loading dan sembunyikan form
        //             $(".loading").removeClass("hidden").addClass("flex");
        //             $("#serve-product-data-modal form").addClass("hidden");
        //         },
        //         success: function(data)
        //         {
        //             // Sembunyikan loading dan tampilkan form
        //             $(".loading").removeClass("flex").addClass("hidden");
        //             $("#serve-product-data-modal form").removeClass("hidden");
        //             // Menampilkan data pada field modal
        //             $('#serve-product-data-modal input[name="name"]').val(data.name);
        //             $('#serve-product-data-modal select[name="unit"]').val(data.unit).trigger('change');
        //             $('#serve-product-data-modal .image-preview').attr('src', data.image);
        //             // Merubah attribut pada form
        //             $("#serve-product-data-modal form").attr("action", `{{ url("owner/produk/bahan-baku") }}/${id}/update`);
        //             $("#serve-product-data-modal form").attr("method", "POST");
        //             $("#serve-product-data-modal form").append(`@method("PUT")`);
        //             $("#serve-product-data-modal input[type='file']").removeAttr(`required`);
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             // Menampilkan pesan error jika Ajax gagal
        //             alert('Terjadi kesalahan saat mengambil data!');
        //         }
        //     })
        // }

        // function addRawProductStock()
        // {
        //     $("#serve-product-quantity-modal span#unit-tambah").html($("#serve-product-quantity-modal input[name='quantity']").val());
        //     $("#serve-product-quantity-modal span#unit-hasil").html(parseInt($("#serve-product-quantity-modal span#unit-sekarang").html()) + parseInt($("#serve-product-quantity-modal input[name='quantity']").val()));
        // }

        // function editRawProductQuantity(id)
        // {
        //     $.ajax({
        //         url: `{{ url("owner/produk/bahan-baku") }}/${id}/edit`,
        //         method: 'GET',
        //         dataType: 'json',
        //         beforeSend: function()
        //         {
        //             // tampilkan loading dan sembunyikan form
        //             $(".loading").removeClass("hidden").addClass("flex");
        //             $("#serve-product-quantity-modal form").addClass("hidden");
        //         },
        //         success: function(data)
        //         {
        //             // sembunyikan loading dan tampilkan form
        //             $(".loading").removeClass("flex").addClass("hidden");
        //             // tampilkan form sembunyikan loading
        //             $("#serve-product-quantity-modal form").removeClass("hidden");
        //             $("#serve-product-quantity-modal span#unit-sekarang").html(data.quantity);
        //             // merubah attribute form
        //             $("#serve-product-quantity-modal form").attr("action", `{{ url("owner/produk/bahan-baku/kuantitas") }}/${id}/update`);
        //             $("#serve-product-quantity-modal form").attr("method", "POST");
        //             $("#serve-product-quantity-modal form").append(`@method("PUT")`);
        //         }
        //     })
        // }

        // function closeRawProductData()
        // {
        //     // Membersihkan value dari semua form
        //     $('#vertical-form input[name="name"]').val("");
        //     $('#vertical-form select[name="unit"]').val($('#serve-product-data-modal #vertical-form select[name="unit"] option:first').val()).trigger('change');
        //     // Hapus gambar preview jika ada
        //     $('.image-preview').attr('src', '');
        //     // Hapus pesan error
        //     $(".border-red-500").removeClass("border-red-500"); 
        //     $('.text-red-500').text('');
        // }

        function addInputElement() {
            const inputContainer = document.querySelector('.input-container');
            const newInput = document.createElement('div');
            newInput.className="flex gap-2";
            newInput.innerHTML = `<select data-hide-search="true" class="select2 w-full border" name="recipe[]" required>
                                    <option value="">--- Pilih Resep ---</option>
                                    @foreach ($raw_product_owners as $item)
                                            <option value="{{ $item->Product->id }}">{{ $item->Product->name }}</option>
                                    @endforeach
                                </select>
                                <input type="number" step="any" class="input border h-10" placeholder="Masukkan jumlah bahan jadi" name="quantity[]" required>
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