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
    <!-- <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> -->
  </head>
<body class="h-screen lg:overflow-y-hidden" x-data="beritaPage" >
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
            <x-sidebar :active="'berita'"></x-sidebar>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-full lg:md:h-[calc(100vh-120px)] px-2 py-6 lg:overflow-y-auto">
        <div class="px-6 pb-4">
            <!-- Judul -->
            <h5 class="font-bold tracking-widest bg-[#3B6BA2] text-gray-50 text-center rounded-xl w-full py-3 text-xl flex items-center justify-center shadow-md">
                Edit Berita
            </h5>
        </div>
        <div class="mx-10">
            <!-- Tambahkan x-data di form wrapper -->
            <form action="{{ route('admin.editberita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-4 w-full">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full">
                        <label for="judul" class="md:w-15 font-medium">Judul</label>
                        <div class="flex-1">
                            <input
                                type="text"
                                name="judul"
                                id="judul"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('judul') border border-red-500 @enderror"
                                value="{{ old('judul', $berita->judul) }}"
                            >
                            @error('judul')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center gap-2 w-full">
                        <label for="penulis" class="md:w-15 font-medium">Penulis</label>
                        <div class="flex-1">
                            <input
                                type="text"
                                name="penulis"
                                id="penulis"
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('penulis') border border-red-500 @enderror"
                                value="{{ old('penulis', Auth::user()->fullname) }}"
                            >
                            @error('penulis')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <label for="tanggal" class="md:w-15 font-medium">Tanggal</label>
                        <div class="flex-1">
                            <input
                                type="date"
                                name="tanggal"
                                id="tanggal" 
                                class="w-full bg-gray-50 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] @error('tanggal') border border-red-500 @enderror"
                                value="{{ old('tanggal', $berita->tanggal) }}"
                            >
                            @error('tanggal')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                    </div>
                    <div class="flex flex-col md:flex-row md:items-start gap-2 w-full">
                        <label for="gambar" class="md:w-20 font-medium">Gambar</label>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-md p-2 border border-gray-200 @error('gambar') border border-red-500 @enderror">
                                {{-- Input Upload --}}
                                <input
                                    type="file"
                                    name="gambar"
                                    id="gambar"
                                    accept="image/*"
                                    class="w-full bg-transparent focus:outline-none focus:ring-2 focus:ring-[#F08619]
                                    file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-[#F08619] file:text-white
                                    hover:file:bg-[#d97706]
                                    focus:outline-none focus:ring-2 focus:ring-[#F08619]"
                                    onchange="previewImage(event)"
                                >
                                @error('gambar')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                {{-- Preview Gambar --}}
                                <div class="mt-2">
                                    <img 
                                        id="gambar-preview" 
                                        class="w-full h-40 object-contain rounded-md border border-gray-300 shadow-sm" 
                                        src="{{ isset($berita) && $berita->gambar ? asset('storage/' . $berita->gambar) : '#' }}" 
                                        alt="Preview"
                                        style="{{ isset($berita) && $berita->gambar ? '' : 'display: none;' }}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-start gap-2 w-full">
                        <label for="deskripsi" class="md:w-20 font-medium mt-2">Deskripsi</label>
                        <div class="flex-1">
                            <!-- Quill editor container -->
                            <div id="quill-editor" class="bg-white rounded-md border border-gray-300" style="min-height: 150px;"></div>
                            @error('deskripsi')
                                <span class="deskripsi-error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            
                            <!-- Hidden textarea untuk submit -->
                            <textarea
                                name="deskripsi"
                                id="deskripsi"
                                class="hidden"
                            >{{ old('deskripsi', $berita->isi) }}</textarea>
                        </div>
                    </div>
                    

                </div>

                <div class="flex mt-4 items-center gap-2">
                    <button
                        type="submit"
                        name="status"
                        value="publish"
                        class="bg-green-600 text-white font-semibold tracking-wider py-2 px-6 mt-2 rounded-md hover:bg-green-700 transition-colors">
                        Publish
                    </button>
                    <button
                        type="submit"
                        name="status"
                        value="draft"
                        class="bg-yellow-500 text-white font-semibold tracking-wider py-2 px-6 mt-2 rounded-md hover:bg-yellow-600 transition-colors">
                        Draft
                    </button>
                    <a
                        href="{{ route('admin.kelolaberita') }}"
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