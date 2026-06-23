<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link
      rel="stylesheet"  
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
</head>
<body class="h-screen lg:overflow-y-hidden" x-data="allPage">
    <x-nav-baar></x-nav-baar>
    <div class="flex mt-31">
            @if(session('success'))
<div id="toast" class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    <i class="fas fa-check-circle mr-3"></i> {{ session('success') }}
</div>
@endif
         <!-- Sidebar -->
         <div class="h-screen w-[300px] bg-[#F08619] px-4 py-15 shadow-lg rounded-lg lg:block hidden">
            <x-sidebar :active="'dokumen'"></x-sidebar>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] lg:md:h-[calc(100vh-120px)] p-2 overflow-y-auto">
                <div class="px-6 py-4">
                    <!-- Judul -->
                    <h5 class="font-bold tracking-widest bg-[#3B6BA2] text-gray-50 text-center rounded-xl w-full py-3 text-xl flex items-center justify-center shadow-md">
                        DAFTAR DOKUMEN
                    </h5>

                    <!-- Controls -->
                    <div class="flex flex-col md:flex-row gap-1 mt-6 items-center">
                        <!-- Pencarian -->
                        <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-2 bg-white shadow-sm w-full md:w-auto">
                            <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                            <input
                                type="text"
                                placeholder="Cari dokumen..."
                                class="outline-none w-full"
                                id="searchInput"
                                x-model="search"
                            >
                        </div>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('admin.tambahdokumen') }}" 
                            class="flex items-center gap-2 bg-green-600 text-gray-50 px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition">
                            <i class="fa-solid fa-plus"></i>
                            Tambah Dokumen
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg shadow-md mx-6">
                <table class="min-w-full bg-white border border-gray-300 text-sm">
                    <thead class="bg-[#CCCACA] font-bold text-base">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">No</th>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">Judul</th>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">File</th>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $index => $document)
                        <tr id="documentRow" class="all-row border-b hover:bg-gray-100"
                        x-show="
                                (
                                    '{{ strtolower($document->judul) }}'.includes(search.toLowerCase())
                                )">
                            <td class="px-6 py-3 font-roboto tracking-wide text-base">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-roboto tracking-wide text-base">{{ $document->judul }}</td>
                            <td class="px-6 py-3 font-roboto tracking-wide text-base">
                                <a class="text-[#008CFF] hover:underline" href="{{ asset('storage/' . $document->file) }}" target="_blank">{{ ($document->judul) }}</a>
                            </td>
                            <td class="px-6 py-3 font-roboto tracking-wide text-base flex items-center">
                            <a href="{{ route('admin.editdokumen', $document->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form id="delete-form-{{ $document->id }}" action="{{ route('admin.keloladokumen.delete', $document->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="openDeleteModal('{{ $document->id }}', '{{ $document->judul }}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded ml-2">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            </td>
                        </tr>
                        @endforeach
                        <tr x-show="showNoResults">
                            <td colspan="4" class="px-6 py-3 font-roboto tracking-wide text-base text-center">
                                Dokumen tidak ditemukan
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div
    x-show="showDeleteModal"
    class="fixed inset-0 z-50 flex items-center justify-center">

    <div
        class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-center mb-4">
            <div class="bg-[#F08619] rounded-full p-3">
                <i class="fas fa-trash text-white text-3xl"></i>
            </div>
        </div>
        <h3 class="text-xl font-bold text-center mb-2">
            Konfirmasi Hapus
        </h3>

        <p class="text-gray-600 text-center mb-6">
            Apakah Anda yakin ingin menghapus 
            <span class="font-semibold" x-text="deleteName"></span>?
        </p>

        <div class="flex gap-3 justify-center">

            <button
                @click="showDeleteModal = false"
                class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Batal
            </button>

            <button
                @click="confirmDelete()"
                class="px-6 py-2 bg-[#F08619] text-white rounded-lg hover:bg-[#0970A5] transition">
                Ya, Hapus
            </button>

        </div>
    </div>
</div>
</body>
</html>