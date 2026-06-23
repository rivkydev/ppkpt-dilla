@php
    $user = Auth::user();
@endphp
<header class="z-50 fixed inset-x-0 top-0 bg-white w-full shadow-md" x-data="{ isOpen: false }">
        <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
          <div class="flex lg:flex-1">
            <a href="#beranda" class="-m-5 p-3">
              <img class="h-18 w-auto" src="{{ asset('img/ppkpt.png') }}" alt="">
            </a>
          </div>
          <div x-data="{ showProfileMenu: false }" class="hidden lg:flex lg:flex-1 lg:justify-end items-center gap-3 relative">
            <!-- Info Pengguna -->
            <div class="flex flex-col text-right">
                <h4 class="font-normal text-gray-900 tracking-wider text-lg text-center">{{ $user->fullname }}</h4>
                <h5 class="font-medium text-gray-900 tracking-wider text-base text-center">{{ucfirst($user->role)}}</h5>
            </div>
            <img
                @click="showProfileMenu = !showProfileMenu"
                class="w-12 h-12 rounded-full object-cover border-2 border-[#F08619] cursor-pointer"
                src="{{ file_exists(public_path('storage/' . $user->profile)) ? asset('storage/' . $user->profile) : asset('img/user.webp') }}" alt="">

            <!-- Dropdown Menu -->
            <div
                x-show="showProfileMenu"
                @click.outside="showProfileMenu = false"
                x-transition
                class="absolute top-full right-0 mt-2 w-48 bg-white shadow-lg rounded-md z-50">
                <a href="/editprofil" class="flex items-center gap-3 px-5 py-3 text-gray-900 hover:bg-[#F08619] hover:text-white font-roboto text-sm rounded-md tracking-wider">
                <span class="bg-[#F08619] text-white rounded-full w-9 h-9 flex items-center justify-center">
                    <i class="fa-solid fa-user text-sm"></i>
                </span>
                Edit Profil
                </a>
                <a href="/logout" class="flex items-center gap-3 px-5 py-3 text-gray-900 hover:bg-[#F08619] hover:text-white font-roboto text-sm rounded-md tracking-wider">
                <span class="bg-[#F08619] text-white rounded-full w-9 h-9 flex items-center justify-center">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i>
                </span>
                Keluar
                </a>
            </div>
            </div>
        </nav>

    </header>