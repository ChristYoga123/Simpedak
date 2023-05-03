@extends('layouts.owner.app')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Data Transaksi
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <!-- BEGIN: Add Modal Button --->
        <a href="javascript:;" data-toggle="modal" data-target="#transaction-modal" class="button text-white bg-theme-1 shadow-md mr-2">+ Tambah Transaksi</a>
        <!-- END: Add Modal Button --->
        <!-- BEGIN: Add Modal Transaction --->
        <div class="modal" id="transaction-modal">
            <div class="modal__content relative"> 
                <a data-dismiss="modal" href="javascript:;" class="absolute right-0 top-0 mt-3 mr-3"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Data Transaksi
                    </h2>
                </div>
                <div class="loading justify-center mt-5 mb-3 gap-5 hidden">
                    <i data-loading-icon="puff" class="w-10 h-10 justify-center"></i> 
                </div>
                <div class="p-5" id="vertical-form">
                    <div class="preview">
                        <form action="{{ route("owner.transaction.store") }}" method="POST">
                            @csrf
                            <div> 
                                <label>Nama</label>
                                <input name="name" type="text" class="input w-full border mt-2" placeholder="Masukkan nama pembeli" required>
                            </div>

                            <div class="mt-3"> 
                                <label>Kontak</label>
                                <input name="contact" type="number" class="input w-full border mt-2" placeholder="Masukkan kontak pembeli" required>
                            </div>

                            <div class="mt-3"> 
                                <label>Alamat</label>
                                <input name="address" type="text" class="input w-full border mt-2" placeholder="Masukkan alamat pembeli" required>
                            </div>
                            <div class="mt-3">
                                <label>Produk yang dibeli</label> 
                                <div class="flex gap-2 mt-2" id="product-select">
                                    <select data-hide-search="true" class="select2 w-full" name="product[]">
                                        <option value="">---Pilih produk jadi---</option>
                                        @foreach ($serve_products as $serve_product)
                                            <option value="{{ $serve_product->id }}">{{ $serve_product->Product->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" class="input border h-10" placeholder="Masukkan jumlah bahan jadi" name=" quantity[]">
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
        <!-- END: Begin Modal Transaction--->

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
                <th class="border-b-2 text-center whitespace-no-wrap">KONTAK</th>
                <th class="border-b-2 text-center whitespace-no-wrap">ALAMAT</th>
                <th class="border-b-2 text-center whitespace-no-wrap">PRODUK</th>
                <th class="border-b-2 text-center whitespace-no-wrap">TOTAL</th>
                <th class="border-b-2 text-center whitespace-no-wrap">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td class="border-b">
                        <div class="font-medium whitespace-no-wrap">{{ $transaction->name }}</div>
                    </td>
                    <td class="text-center border-b">
                        <div class="font-medium whitespace-no-wrap">{{ $transaction->contact }}</div>
                    </td>
                    <td class="text-center border-b">
                        <div class="font-medium whitespace-no-wrap">{{ $transaction->address }}</div>
                    </td>
                    <td class="text-center">
                        <ul>
                            @forelse ($transaction->TransactionDetails as $transaction_detail)
                                <li>{{ $transaction_detail->ProductOwner->Product->name }} ({{ $transaction_detail->quantity }})</li>
                            @empty
                                <li>Tidak ada resep</li>
                            @endforelse
                        </ul>
                    </td>
                    <td class="text-center border-b">
                        <div class="font-medium whitespace-no-wrap">Rp{{ $transaction->total_price }},00</div>
                    </td>
                    <td class="border-b w-5">
                        <div class="flex sm:justify-center items-center">
                            <a class="flex items-center mr-3 text-yellow-700" href="javascript:;" data-toggle="modal" data-target="#transaction-modal" onclick="editTransactionData({{ $transaction->id }})"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit Data</a>
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
        // function addInputElement()
        function addInputElement() {
            const inputContainer = document.querySelector('.input-container');
            const newInput = document.createElement('div');
            newInput.className="flex gap-2";
            newInput.innerHTML = `  <select data-hide-search="true" class="select2 w-full" name="product[]">
                                    <option value="">---Pilih produk jadi---</option>
                                        @foreach ($serve_products as $serve_product)
                                            <option value="{{ $serve_product->id }}">{{ $serve_product->Product->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" class="input border h-10" placeholder="Masukkan jumlah bahan jadi" name=" quantity[]">
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

        function editTransactionData(id)
        {
            $.ajax({
                url: `{{ url("owner/transaksi") }}/${id}/show`,
                method: 'GET',
                dataType: 'json',
                beforeSend: function()
                {
                    // tampilkan loading dan sembunyikan form
                    $(".loading").removeClass("hidden").addClass("flex");
                    $("#transaction-modal form").addClass("hidden");
                },
                success: function(data)
                {
                    // sembunyikan loading dan tampilkan form
                    $(".loading").removeClass("flex").addClass("hidden");
                    $("#transaction-modal form").removeClass("hidden");
                    // isi form
                    $("#transaction-modal form").attr("action", `{{ url("owner/transaksi") }}/${id}/update`);
                    $("#transaction-modal form").append(`@method("PUT")`);
                    $('#transaction-modal input[name=name]').val(data.name);
                    $('#transaction-modal input[name=contact]').val(data.contact);
                    $('#transaction-modal input[name=address]').val(data.address);
                }
            });
        }
    </script>
@endpush
@endsection