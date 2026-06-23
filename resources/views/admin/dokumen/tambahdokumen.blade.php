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
            <x-sidebar :active="'dokumen'"></x-sidebar>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-full px-2 py-6 lg:overflow-y-auto">
        <div class="px-6 pb-4">
            <!-- Judul -->
            <h5 class="font-bold tracking-widest bg-[#3B6BA2] text-gray-50 text-center rounded-xl w-full py-3 text-xl flex items-center justify-center shadow-md">
                Tambah Dokumen
            </h5>
        </div>
        <div class="mx-10">
            <!-- Tambahkan x-data di form wrapper -->
            <form action="{{ route('admin.keloladokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col gap-4 w-full">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full">
                        <label for="judul" class="md:w-40 font-medium">Judul</label>
                        <div class="flex-1">
                            <input
                                type="text"
                                name="judul"
                                id="judul"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('judul') border border-red-500 @enderror"
                                value="{{ old('judul') }}"
                            >
                            @error('judul')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full">
                        <label for="file" class="md:w-40 font-medium">File</label>
                        <div class="flex-1">
                            <input
                                type="file"
                                name="file"
                                id="file" 
                                accept=".pdf,.doc,.docx"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('file') border border-red-500 @enderror
                                    file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-[#F08619] file:text-white
                                    hover:file:bg-[#d97706]
                                    focus:outline-none focus:ring-2 focus:ring-[#F08619]"
                            >
                            @error('file')
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
                        href="{{ route('admin.keloladokumen') }}"
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