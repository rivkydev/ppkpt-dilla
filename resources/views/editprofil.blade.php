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
    <div class="z-50 fixed inset-x-0 top-0 bg-white w-full shadow-md p-6 lg:px-8">
        <img class="h-18 w-auto" src="img/ppkpt.png" alt="">
    </div>
    <div class="mt-36 px-4 lg:px-15">
    @php
        $user = Auth::user();

        if ($user->role === 'admin') {
            $url = route('admin.home');
        } elseif ($user->role === 'pelapor') {
            $url = route('user.home');
        } elseif ($user->role === 'satgas') {
            $url = route('satgas.home');
        }
    @endphp
        <a href="{{ $url }}" class="flex items-center gap-2 text-[#F08619] font-roboto font-semibold tracking-wider hover:text-[#3B6BA2] transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Beranda
        </a>

        <div class="mt-4 flex flex-col gap-4 items-center justify-center shadow-xl w-full max-w-2xl mx-auto p-6 rounded-lg bg-white">
        @if(session('success'))
                <div 
                    x-data="{ show: true }" 
                    x-init="setTimeout(() => show = false, 5000)" 
                    x-show="show" 
                    x-transition 
                    class="fixed top-28 right-4 z-50"
                >
                    <div class="flex items-center px-6 py-4 rounded-lg shadow-lg bg-green-500 text-white">
                        <i class="fas fa-check-circle mr-3"></i>
                        <div class="text-md font-semibold">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            @endif
            @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                class="fixed top-28 right-4 z-50">
                <div class="flex items-center px-6 py-4 rounded-lg shadow-lg bg-red-500 text-white">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <div class="text-md font-semibold">
                        {{ $errors->first() }}
                    </div>
                </div>
            </div>
            @endif
            <form action="{{ route('editprofil.update') }}" method="POST" enctype="multipart/form-data" class="w-full flex flex-col gap-1">
                @csrf
                <div x-data="{ preview: '{{ file_exists(public_path('storage/' . Auth::user()->profile)) ? asset('storage/' . Auth::user()->profile) : asset('img/user.webp') }}' }" class="flex flex-col items-center gap-4">
                    <!-- Image -->
                    <label for="profile" class="cursor-pointer">
                        <img 
                            :src="preview" 
                            class="w-24 h-24 rounded-full object-cover border-2 border-[#F08619]" 
                            alt="Foto Profil">
                    </label>

                    <!-- Input File -->
                    <input 
                        type="file" 
                        id="profile" 
                        name="profile" 
                        hidden accept="image/*"
                        @change="preview = URL.createObjectURL($event.target.files[0])">
                </div>

                <h6 class="font-semibold text-lg text-center">{{ ucfirst(Auth::user()->role) }}</h6>

                <div class="flex flex-col gap-4 w-full">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full">
                        <label for="fullname" class="md:w-40 font-medium">Nama Lengkap</label>
                        <input 
                            type="text" 
                            name="fullname" 
                            id="fullname" 
                            value="{{ Auth::user()->fullname }}" 
                            class="flex-1 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619]" 
                            readonly>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full">
                        <label for="email" class="md:w-40 font-medium">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') ? old('email') : Auth::user()->email }}" 
                            class="flex-1 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full">
                        <label for="password" class="md:w-40 font-medium">Ganti Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="Password Baru" 
                            class="flex-1 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full">
                        <label for="password_confirmation" class="md:w-40 font-medium">Konfirmasi Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            placeholder="Konfirmasi Password Baru" 
                            class="flex-1 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                    </div>
                </div>

                <div class="flex justify-center">
                    <button 
                        type="submit" 
                        class="bg-[#F08619] text-white font-semibold tracking-wider py-2 px-6 mt-2 rounded-md hover:bg-[#3B6BA2] transition-colors">
                        Perbarui Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>