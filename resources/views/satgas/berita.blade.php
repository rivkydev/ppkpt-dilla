<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ config('app.name') }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
</head>
<body class="h-screen lg:overflow-y-hidden">
    <x-nav-baar></x-nav-baar>
    
    <div class="flex mt-31">
        <div class="h-screen w-[300px] bg-[#0970A5] px-4 py-15 shadow-lg rounded-lg lg:block hidden">
            <x-sidebarr :active="'berita'"></x-sidebarr>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] h-[calc(100vh-120px)]  p-5 overflow-y-auto">
                <div class="px-6 py-4">
                    <!-- Judul -->
                    <h5 class="font-bold tracking-widest bg-[#0970A5] text-gray-50 text-center rounded-xl w-full py-3 text-xl flex items-center justify-center shadow-md">
                        BERITA 
                    </h5>
                </div>

                <div class="overflow-x-auto rounded-lg mx-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Card Berita -->
                     @foreach ($beritas as $berita)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">

                        <div class="p-5">
                            <img src="{{ asset('storage/' . $berita->gambar) }}" class="w-full h-[200px] object-cover mb-2 rounded-l" alt="">
                            <h2 class="font-semibold text-lg text-gray-800 mb-2">
                                {{ $berita->judul }}
                            </h2>

                            <p class="text-sm mb-3 text-[#F08619] flex items-center gap-3">
                                <span>
                                    <i class="fa-solid fa-calendar mr-1"></i>
                                    {{ $berita->created_at->format('d F Y') }}
                                </span>

                                <span>
                                    <i class="fa-solid fa-user mr-1"></i>
                                    {{ $berita->penulis ?? 'Admin PPKPT' }}
                                </span>
                            </p>

                            <p class="text-sm text-gray-600 mb-4">
                                {!! \Illuminate\Support\Str::limit($berita->isi, 200, '...') !!}
                            </p>

                            <a href="{{ route('satgas.beritaDetail', $berita->id) }}"
                            class="text-[#0970A5] text-sm font-medium hover:underline">
                                Baca Selengkapnya →
                            </a>
                        </div>

                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</body>
</html>