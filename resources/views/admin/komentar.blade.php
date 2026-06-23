<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
<!-- 
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
</head>

<body class="h-screen lg:overflow-y-hidden">
    <x-nav-baar></x-nav-baar>
        <div class="flex mt-31">

      <div class="w-[300px] bg-[#F08619] px-4 py-15 shadow-lg rounded-lg lg:block hidden">

            <x-sidebar :active="'komentar'"></x-sidebar>

            <!-- Wrapper kanan -->
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] lg:md:h-[calc(100vh-120px)] p-2 overflow-y-auto">
                <div class="px-6 py-4">
                    <!-- Judul -->
                    <h5 class="font-bold tracking-widest bg-[#3B6BA2] text-gray-50 text-center rounded-xl w-full py-3 text-xl flex items-center justify-center shadow-md">
                        KOMENTAR
                    </h5>
                </div>
                <div class="overflow-x-auto rounded-lg shadow-md mx-6">


                {{-- Card utama --}}
                <div class="bg-white h-auto rounded-2xl shadow-lg p-6 ">

                    {{-- Table --}}
                    @if($messages->count() > 0)

                        <div class="overflow-x-auto">

                            <table class="min-w-full">

                                {{-- Header table --}}
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 text-sm uppercase">

                                        <th class="px-6 py-3 text-left rounded-l-lg">No</th>
                                        <th class="px-6 py-3 text-left">Nama</th>
                                        <th class="px-6 py-3 text-left">Email</th>
                                        <th class="px-6 py-3 text-left">Pesan</th>
                                        <th class="px-6 py-3 text-left rounded-r-lg">Tanggal</th>

                                    </tr>
                                </thead>

                                {{-- Body --}}
                                <tbody class="divide-y">

                                    @foreach($messages as $index => $message)

                                    <tr class="hover:bg-gray-50 transition">

                                        <td class="px-6 py-4 font-medium">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="px-6 py-4 font-semibold text-gray-800">
                                            {{ $message->nama }}
                                        </td>

                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $message->email }}
                                        </td>

                                        <td class="px-6 py-4 max-w-sm">
                                            <div class="truncate"
                                                title="{{ $message->pesan }}">
                                                {{ $message->pesan }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $message->created_at->format('d/m/Y H:i') }}
                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        {{-- Empty state --}}
                        <div class="text-center py-12">

                            <i class="fa fa-comments text-gray-300 text-5xl mb-4"></i>

                            <p class="text-gray-500 text-lg">
                                Belum ada komentar
                            </p>

                            <p class="text-gray-400 text-sm">
                                Komentar dari pengguna akan muncul di sini
                            </p>

                        </div>

                    @endif

                </div>


                </div>
            </div>
                

            </div>

        </div>

    </div>


</body>
</html>
