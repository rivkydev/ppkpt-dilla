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
<body class="h-screen lg:overflow-y-hidden">
    <x-nav-baar>
        <x-slot name="label1"></x-slot>
        <x-slot name="label2"></x-slot>
        <x-slot name="label3"></x-slot>
        <x-slot name="label4"></x-slot>
        <x-slot name="label5">Admin</x-slot>
    </x-nav-bar>
    <div class="flex mt-31">
        <!-- Sidebar -->
        <div class="h-screen w-[300px] bg-[#F08619] px-4 py-15 shadow-lg rounded-lg lg:block hidden">
            <x-sidebar :active="'pengguna'"></x-sidebar>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-full lg:h-[calc(100vh-120px)] px-2 py-6 lg:overflow-y-auto">
        <div class="px-6 pb-4">
            <!-- Judul -->
            <h5 class="font-bold tracking-widest bg-[#3B6BA2] text-gray-50 text-center rounded-xl w-full py-3 text-xl flex items-center justify-center shadow-md">
                Tambah Pengguna
            </h5>
        </div>
        <div class="mx-10">
            <!-- Tambahkan x-data di form wrapper -->
            <form action="{{ route('admin.kelolapengguna.store') }}" method="POST" x-data="{ selectedRole: '{{ old('role') }}' }">
                @csrf

                <div class="flex flex-col gap-4 w-full">

                    <!-- Role -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full">
                        <label for="role" class="md:w-40 font-medium">Role</label>
                        <div class="flex-1">
                            <select
                                name="role"
                                id="role"
                                x-model="selectedRole"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('role') border border-red-500 @enderror"
                            >
                                <option value="" selected disabled>Pilih Role</option>
                                <option value="pelapor" {{ old('role') == 'pelapor' ? 'selected' : '' }}>Pelapor</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="satgas" {{ old('role') == 'satgas' ? 'selected' : '' }}>Satgas</option>
                            </select>
                            @error('role')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full" x-show="selectedRole">
                        <label for="fullname" class="md:w-40 font-medium">Nama Lengkap</label>
                        <div class="flex-1">
                            <input
                                type="text"
                                name="fullname"
                                id="fullname"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('fullname') border border-red-500 @enderror"
                                value="{{ old('fullname') }}"
                            >
                            @error('fullname')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full" x-show="selectedRole">
                        <label for="username" class="md:w-40 font-medium">Username</label>
                        <div class="flex-1">
                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('username') border border-red-500 @enderror"
                                value="{{ old('username') }}"
                            >
                            @error('username')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- NIM/NIDN -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full" 
                        x-show="selectedRole === 'pelapor'">
                        <label for="nim_nidn" class="md:w-40 font-medium">NIM/NIDN</label>
                        <div class="flex-1">
                            <input
                                type="text"
                                name="nim_nidn"
                                id="nim_nidn"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('nim_nidn') border border-red-500 @enderror"
                                value="{{ old('nim_nidn') }}"
                            >
                            @error('nim_nidn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full" x-show="selectedRole">
                        <label for="email" class="md:w-40 font-medium">Email</label>
                        <div class="flex-1">
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('email') border border-red-500 @enderror"
                                value="{{ old('email') }}"
                            >
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full" x-show="selectedRole">
                        <label for="password" class="md:w-40 font-medium">Password</label>
                        <div class="flex-1">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('password') border border-red-500 @enderror">
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full" x-show="selectedRole">
                        <label for="password_confirmation" class="md:w-40 font-medium">Konfirmasi Password</label>
                        <div class="flex-1">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('password_confirmation') border border-red-500 @enderror">
                            @error('password_confirmation')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full" 
                        x-show="selectedRole === 'pelapor'">
                        <label for="status" class="md:w-40 font-medium">Status</label>
                        <div class="flex-1">
                            <select
                                name="status"
                                id="status"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('status') border border-red-500 @enderror"
                            >
                                <option value="">Pilih Status</option>
                                <option value="Pimpinan" {{ old('status') == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                <option value="Dosen" {{ old('status') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="Tenaga Pendidik" {{ old('status') == 'Tenaga Pendidik' ? 'selected' : '' }}>Tenaga Pendidik</option>
                                <option value="Satpam" {{ old('status') == 'Satpam' ? 'selected' : '' }}>Satpam</option>
                                <option value="OB" {{ old('status') == 'OB' ? 'selected' : '' }}>OB</option>
                                <option value="Mahasiswa" {{ old('status') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex mt-4 items-center gap-2">
                    <button
                        type="submit"
                        class="bg-[#F08619] text-white font-semibold tracking-wider py-2 px-6 mt-2 rounded-md hover:bg-[#3B6BA2] transition-colors">
                        Buat
                    </button>
                    <a
                        href="{{ route('admin.kelolapengguna') }}"
                        class="bg-gray-300 text-gray-700 font-semibold tracking-wider py-2 px-6 mt-2 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>
        </div>
    </div>
    
</body>
</html>