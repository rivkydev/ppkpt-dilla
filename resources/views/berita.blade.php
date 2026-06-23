<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PPKPT ITH</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link
      rel="stylesheet"  
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
  </head>
<body>
<x-nav-barr>
        <x-slot name="label1">Beranda</x-slot>
        <x-slot name="label2">Berita</x-slot>
        <x-slot name="label3"> @auth
        @if (auth()->user()->role == 'admin')
            Statistik
        @else
            Tentang Kami
        @endif
    @else
        Tentang Kami
    @endauth</x-slot>
    </x-nav-barr>
    <div class="flex flex-col lg:flex-row mt-35 gap-10 px-5 sm:px-25 lg:px-25 max-w-7xl mx-auto">
    <!-- Bagian Kiri (Konten Utama) -->
    <div class="w-full lg:w-2/3">
        <img class="w-full h-auto max-h-[400px] object-cover rounded-lg mx-auto" src="{{ asset('storage/' . $berita->gambar) }}" alt="">
        <h1 class="text-2xl font-bold mt-4">
        {{ $berita->judul }}
        </h1>
        <div class="flex gap-4 mt-2 flex-wrap text-sm font-semibold">
        <h6 class="flex items-center gap-1">
            <i class="fa-solid fa-user-tag text-[#F08619]"></i> {{ $berita->penulis }}
        </h6>
        <h6 class="flex items-center gap-1">
            <i class="fa-solid fa-calendar-day text-[#F08619]"></i> {{ $berita->tanggal }}
        </h6>
        </div>
        <p class="text-justify mt-4">
        {!! $berita->isi !!}
        </p>
    </div>

    <!-- Bagian Kanan (Sidebar) -->
    <div class="w-full lg:w-1/3 shadow-lg rounded-lg">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-5 p-5 ">
            <!-- Card 1 -->
            <div class="flex flex-col">
            @foreach ($beritas->shuffle()->take(4) as $berita)
                <img class="w-full h-36 object-cover rounded-lg" src="{{ asset('storage/' . $berita->gambar) }}" alt="">
                <a class="font-semibold text-justify tracking-tight mt-2 hover:text-[#F08619]" href="{{ route('berita', $berita->id) }}">
                {{ $berita->judul }}
                </a>
                <div class="flex gap-2 my-2 flex-wrap text-xs font-semibold">
                <h6 class="flex items-center gap-1">
                    <i class="fa-solid fa-user-tag text-[#F08619]"></i> {{ $berita->penulis }}
                </h6>
                <h6 class="flex items-center gap-1">
                    <i class="fa-solid fa-calendar-day text-[#F08619]"></i> {{ $berita->tanggal }}
                </h6>
                </div>
            @endforeach
            </div>
        </div>
        <!-- Link lainnya -->
        <div>
            <a href="#" class="text-[#008CFF] hover:text-[#3B6BA2] hover:underline block text-center">
            Berita lainnya...
            </a>
        </div>
    </div>
    </div>

    <x-faq></x-faq>>
</body>
</html>