@php
    $user = Auth::user();
@endphp
@props(['active'])
            <a href="{{ route ('satgas.home') }}" class="flex items-center my-1 mb-2 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 transition-colors duration-200 {{ $active === 'beranda' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-house"></i>
                <h5 class="font-semibold">Beranda</h5>
            </a>
            <a href="{{ route ('satgas.laporanmasuk') }}" class="flex items-center my-1 mb-2 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 transition-colors duration-200 {{ $active === 'laporan-masuk' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-file-arrow-down"></i>
                <h5 class="font-semibold">Laporan Masuk</h5>
                </a>
            <a href="{{ route('satgas.laporanditangani') }}" class="flex items-center my-1 mb-2 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 transition-colors duration-200 {{ $active === 'laporan-ditangani' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-folder-open"></i>
                <h5 class="font-semibold">Laporan Ditangani</h5>
            </a>
            <a href="{{ route('satgas.laporanselesai') }}" class="flex items-center my-1 mb-2 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 transition-colors duration-200 {{ $active === 'laporan-selesai' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-square-check"></i>
                <h5 class="font-semibold">Laporan Selesai</h5>
            </a>
            <a href="{{ route('satgas.berita') }}" class="flex items-center my-1 mb-2 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 transition-colors duration-200 {{ $active === 'berita' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-newspaper"></i>
                <h5 class="font-semibold">Berita</h5>
            </a>

        </div>
        <div x-data="{ sidebar: false }">
            <!-- Tombol Hamburger (hanya tampil jika sidebar belum dibuka) -->
            <div class="flex lg:hidden justify-end p-4">
                <button 
                @click="sidebar = true" 
                class="text-white bg-[#0970A5] px-3 py-2 rounded-lg"
                >
                <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <!-- Sidebar dengan Transition -->
            <div 
                x-show="sidebar"
                x-transition:enter="transition-transform ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition-transform ease-in duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed top-31 w-[250px] h-full bg-[#0970A5] px-6 py-10 shadow-lg rounded-r-lg text-lg z-50 lg:hidden"
                @click.outside="sidebar = false">
                <button 
                @click="sidebar = false" 
                class="text-white bg-[#0970A5] px-3"
                >
                <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="flex flex-col items-center justify-center">
                    <a href="/editprofil">
                    <img class="w-12 h-12 rounded-full object-cover border-2 border-white cursor-pointer" src="{{ file_exists(public_path('storage/' . $user->profile)) ? asset('storage/' . $user->profile) : asset('img/user.webp') }}" alt="">
                    </a>
                    <h5 class="font-bold text-gray-50">{{ $user->fullname }}</h5>
                    <h5 class="font-semibold text-sm text-gray-50">Satgas</h5>
                </div>   

                <!-- Menu Items -->
                <a href="{{ route('satgas.home') }}" class="flex items-center my-1 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 {{ $active === 'beranda' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-house"></i>
                <h5 class="font-semibold">Beranda</h5>
                </a>
                <a href="{{ route ('satgas.laporanmasuk') }}"class="flex items-center my-1 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 {{ $active === 'laporan-masuk' ? 'bg-[#0a5a8c]' : '' }}">
                    <i class="fa-solid fa-file"></i>
                <h5 class="font-semibold">Laporan Masuk</h5>
                </a>
                <a href="{{ route('satgas.laporanditangani') }}" class="flex items-center my-1 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 {{ $active === 'laporan-ditangani' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-folder-open"></i>
                <h5 class="font-semibold">Laporan Ditangani</h5>
                </a>
                <a href="{{ route('satgas.laporanselesai') }}" class="flex items-center my-1 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 {{ $active === 'laporan-selesai' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-square-check"></i>
                <h5 class="font-semibold">Laporan Selesai</h5>
                </a>
                <a href="{{ route('satgas.berita') }}" class="flex items-center my-1 gap-2 text-gray-50 hover:bg-[#0a5a8c] rounded-lg px-2 py-1 hover:mx-2 {{ $active === 'berita' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-newspaper"></i>
                <h5 class="font-semibold">Berita</h5>
                </a>
               
                <a href="/logout" class="flex items-center gap-2 text-[#0970A5] bg-gray-50 my-6 rounded-lg px-4 py-1 {{ $active === 'keluar' ? 'bg-[#0a5a8c]' : '' }}">
                <i class="fa-solid fa-right-from-bracket"></i>
                <h5 class="font-semibold">Keluar</h5>
                </a>
            </div>
            </div>
        

        