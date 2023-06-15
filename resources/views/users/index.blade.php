<x-app-layout>
    <x-slot name="header">
        {{ __('Users') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">

        <div class="inline-flex overflow-hidden mb-4 w-full bg-white rounded-lg shadow-md">
            <div class="flex justify-center items-center w-12 bg-blue-500">
                <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"></path>
                </svg>
            </div>

            <div class="px-4 py-2 -mx-3">
                <div class="mx-3">
                    <span class="font-semibold text-blue-500">Info</span>
                    <p class="text-sm text-gray-600">Sample table page</p>
                </div>
            </div>
        </div>

        <div class="overflow-hidden mb-8 w-full rounded-lg border shadow-xs">
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Surat Usaha</th>
                        <th class="px-4 py-3">Verifikasi</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                    @foreach($users as $user)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm">
                                {{ $user->name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $user->roles->first()->name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <img src="{{ $user->getFirstMediaUrl("avatar") }}" alt="" width="100px" height="100px">
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ $user->Business->getFirstMediaUrl("business-permission-letter") }}">
                                    <button class="btn btn-primary">Download Bukti Usaha</button>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <form action="{{ route("user.update", $user->id) }}" method="post" id="email_verified_at_{{ $user->id }}">
                                    @csrf
                                    @method('PUT')
                                    <select class="select select-bordered w-full max-w-xs" name="email_verified_at" onchange="submitForm({{ $user->id }})">
                                        <option value="" {{ $user->email_verified_at === null ? "selected" : "" }}>Belum Diverifikasi</option>
                                        <option value="Terverifikasi" {{ $user->email_verified_at ? "selected" : "" }}>Terverifikasi</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-cols-9">
                {{ $users->links() }}
            </div>
        </div>

    </div>

    <script>
        function submitForm(userId) {
            const form = document.querySelector("#email_verified_at_" + userId);
            form.submit();
        }
    </script>
</x-app-layout>
