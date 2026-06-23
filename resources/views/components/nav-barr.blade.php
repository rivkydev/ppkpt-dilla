  <header class="z-50 fixed inset-x-0 top-0 bg-white w-full shadow-md" x-data="{ isOpen: false }">
        <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
          <div class="flex lg:flex-1">
            <a href="#beranda" class="-m-5 p-3">
              <img class="h-18 w-auto" src="{{ asset('img/ppkpt.png') }}" alt="">
            </a>
          </div>
          <div class="flex lg:hidden">
            <button @click="isOpen = !isOpen" type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
              <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
              </svg>
            </button>
          </div>
          <div class="hidden lg:flex lg:items-center lg:gap-x-8 relative">
            <!-- Link Navigasi -->
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.home') }}#beranda" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label1 }}</a>
                    <a href="{{ route('admin.home') }}#berita" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label2 }}</a>
                    <a href="{{ route('admin.home') }}#tentang" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label3 }}</a>
                @elseif(auth()->user()->role === 'satgas')
                    <a href="{{ route('satgas.home') }}#beranda" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label1 }}</a>
                    <a href="{{ route('satgas.home') }}#berita" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label2 }}</a>
                    <a href="{{ route('satgas.home') }}#tentang" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label3 }}</a>
                @else
                    <a href="{{ route('user.home') }}#beranda" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label1 }}</a>
                    <a href="{{ route('user.home') }}#berita" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label2 }}</a>
                    <a href="{{ route('user.home') }}#tentang" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label3 }}</a>
                @endif
            @else
                <a href="{{ route('home') }}#beranda" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label1 }}</a>
                <a href="{{ route('home') }}#berita" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label2 }}</a>
                <a href="{{ route('home') }}#tentang" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">{{ $label3 }}</a>
            @endauth

            <!-- Dropdown Dokumen -->
            <div class="relative" x-data="{ isOpen: false }">
              @php
              use App\Models\Document;
                $documents = Document::orderBy('id', 'desc')->get();
              @endphp
                <button @click="isOpen = !isOpen" class="text-lg font-normal text-gray-900 hover:text-[#F08619] font-roboto transition-colors">
                Dokumen
                </button>
                <div x-show="isOpen" @click.outside="isOpen = false" x-transition class="absolute left-0 top-full mt-2 bg-white shadow-lg rounded-md p-4 w-[200px] z-50">
                @if($documents->isEmpty())
                    <span class="block text-sm text-gray-500 py-1">Belum ada dokumen</span>
                @else
                    @foreach ($documents as $document)
                    <a href="{{ asset('storage/' . $document->file) }}" target="_blank" class="block text-base text-gray-900 hover:text-[#F08619] font-roboto py-1">{{ $document->judul }}</a>
                    @endforeach
                @endif
                </div>
            </div>
            </div>
            @php
            $user = Auth::user();
            @endphp
            @auth
            @if (request()->routeIs('admin.home') || request()->routeIs('user.home') || request()->routeIs('berita') || request()->routeIs('satgas.home'))
            <div x-data="{ showProfileMenu: false }" class="hidden lg:flex lg:flex-1 lg:justify-end items-center gap-3 relative">
              <!-- Info Pengguna -->
              <div class="flex flex-col text-right">
                  <h4 class="font-normal text-gray-900 tracking-wider text-lg text-center">{{ $user->fullname }}</h4>
                  <h5 class="font-medium text-gray-900 tracking-wider text-base text-center">{{ ucfirst($user->role) }}</h5>
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
            @else
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
            <a href="/login" class="text-base font-semibold tracking-wide text-gray-50 bg-[#F08619] hover:bg-[#3B6BA2] flex items-center gap-1 font-roboto px-7 py-2 rounded-xl">
              Masuk <i class="fa-solid fa-right-to-bracket"></i>
            </a>
          </div>
          @endif
          @endauth
          @guest
          <div class="hidden lg:flex lg:flex-1 lg:justify-end">
            <a href="/login" class="text-base font-semibold tracking-wide text-gray-50 bg-[#F08619] hover:bg-[#3B6BA2] flex items-center gap-1 font-roboto px-7 py-2 rounded-xl">
              Masuk <i class="fa-solid fa-right-to-bracket"></i>
            </a>
          </div>
          @endguest
        </nav>

        <!-- Mobile Dropdown Menu -->
        <div x-show="isOpen" x-transition class="lg:hidden px-6 py-4 bg-white shadow-md space-y-2 rounded-lg" x-data="{ showDocuments: false }">
        
          @auth
          @if(auth()->user()->role === 'admin')
            <!-- Navigasi Utama -->
          <a @click="isOpen = false" href="{{ route('admin.home') }}#beranda" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label1 }}</a>
          <a @click="isOpen = false" href="{{ route('admin.home') }}#berita" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label2 }}</a>
          <a @click="isOpen = false" href="{{ route('admin.home') }}#tentang" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label3 }}</a>
          @elseif(auth()->user()->role === 'satgas')
          <!-- Navigasi Utama -->
          <a @click="isOpen = false" href="{{ route('satgas.home') }}#beranda" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label1 }}</a>
          <a @click="isOpen = false" href="{{ route('satgas.home') }}#berita" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label2 }}</a>
          <a @click="isOpen = false" href="{{ route('satgas.home') }}#tentang" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label3 }}</a>
          @else
          <a @click="isOpen = false" href="{{ route('user.home') }}#beranda" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label1 }}</a>
          <a @click="isOpen = false" href="{{ route('user.home') }}#berita" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label2 }}</a>
          <a @click="isOpen = false" href="{{ route('user.home') }}#tentang" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label3 }}</a>
          @endif
          @else
          <!-- Navigasi Utama -->
          <a @click="isOpen = false" href="{{ route('home') }}#beranda" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label1 }}</a>
          <a @click="isOpen = false" href="{{ route('home') }}#berita" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label2 }}</a>
          <a @click="isOpen = false" href="{{ route('home') }}#tentang" class="block text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md">{{ $label3 }}</a>
          @endauth
          <!-- Dropdown Dokumen -->
          <button @click="showDocuments = !showDocuments" class="w-full text-left text-base text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-md flex items-center justify-between">
              Dokumen
              <svg :class="{ 'rotate-180': showDocuments }" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
          </button>
          <div x-show="showDocuments" x-transition class="pl-6 space-y-1">
              @if($documents->isEmpty())
                  <span class="block text-sm text-gray-500 py-1">Belum ada dokumen</span>
              @else
                  @foreach ($documents as $document)
                  <a href="{{ asset('storage/' . $document->file) }}" target="_blank" class="block text-base text-gray-900 hover:text-[#F08619] font-roboto py-1">{{ $document->judul }}</a>
                  @endforeach
              @endif
          </div>

          @auth
          <!-- Info User -->
        <div class="flex justify-between items-center mt-4 border-t-2 border-[#F08619]">
            <div class="flex items-center gap-3 pt-4">
                <a href="/editprofil"><img class="w-12 h-12 rounded-full object-cover border-2 border-[#F08619]" src="{{ file_exists(public_path('storage/' . $user->profile)) ? asset('storage/' . $user->profile) : asset('img/user.webp') }}" alt="Foto Pengguna"></a>
                <div>
                    <h4 class="text-gray-900 font-semibold text-base">{{ $user->fullname }}</h4>
                    <h5 class="text-gray-600 text-sm">{{ ucfirst($user->role) }}</h5>
                </div>
            </div>
            <a class="bg-[#F08619] text-white rounded-md px-4 py-2 flex items-center justify-center gap-2 hover:bg-[#3B6BA2] tracking-wider font-roboto" href="/logout">Keluar <i class="fa-solid fa-right-from-bracket text-sm"></i></a>
        </div>
          @else
          <a @click="isOpen = false" href="/login" class="block text-base font-semibold tracking-wide text-white bg-[#F08619] hover:bg-[#3B6BA2] px-4 py-2 rounded-lg mt-2 text-center">
            Masuk <i class="fa-solid fa-right-to-bracket"></i>
          </a>
          @endauth
        </div>
    </header>