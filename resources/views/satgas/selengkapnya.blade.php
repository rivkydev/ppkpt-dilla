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
            <x-sidebarr :active="''"></x-sidebarr>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] h-[calc(100vh-120px)]  p-5 overflow-y-auto">
                <div class="relative mb-6">
                    <h5 class="font-bold tracking-widest 
                                bg-[#0970A5] text-gray-50 
                                rounded-xl w-full py-3 text-xl 
                                text-center shadow-md">
                            BERITA
                    </h5>
                    <a href="#"
   class="btn-back absolute left-4 top-1/2 -translate-y-1/2 text-white text-lg">
    <i class="fa-solid fa-angle-left"></i>
</a>
                </div>

                <div class="overflow-x-auto rounded-lg mx-6">
                   <!-- Card Detail -->
                <div class="bg-white rounded-2xl shadow-lg p-8">

                    <img src="{{ asset('storage/' . $berita->gambar) }}" alt="Gambar Berita" class="w-full h-64 object-contain mb-6">

                    <!-- Judul -->
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 leading-snug">
                        {{ $berita->judul }}
                    </h1>

                    <!-- Meta Info -->
                    <div class="flex items-center text-sm text-[#F08619] mb-6 space-x-4">
                        <span>
                            <i class="fa-solid fa-calendar mr-1"></i>
                            {{ $berita->created_at->format('d F Y') }}
                        </span>

                        <span>
                            <i class="fa-solid fa-user mr-1"></i>
                            {{ $berita->penulis ?? 'Admin PPKPT' }}
                        </span>
                    </div>

                    <!-- Garis -->
                    <div class="border-t border-gray-200 mb-6"></div>

                    <!-- Isi Berita -->
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! $berita->isi !!}
                    </div>

                </div>

                    

                </div>
            </div>
        </div>
    </div>
</body>
</html>