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
            <x-sidebar :active="'berita'"></x-sidebar>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] lg:md:h-[calc(100vh-120px)] p-2 overflow-y-auto">
                <div class="px-6 py-4">
                    <!-- Judul -->
                    <h5 class="font-bold tracking-widest bg-[#3B6BA2] text-gray-50 text-center rounded-xl w-full py-3 text-xl flex items-center justify-center shadow-md">
                        DAFTAR BERITA
                    </h5>

                    <!-- Controls -->
                    <div class="flex flex-col md:flex-row gap-1 mt-6 items-center">
                        <!-- Pencarian -->
                        <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-2 bg-white shadow-sm w-full md:w-auto">
                            <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                            <input
                                type="text"
                                placeholder="Cari berita..."
                                class="outline-none w-full"
                                id="searchInput"
                                x-model="search">
                        </div>

                         <!-- Filter -->
                         <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-2 bg-white shadow-sm w-full md:w-auto">
                            <i class="fa-solid fa-filter text-gray-500"></i>
                            <select x-model="filter" class="outline-none w-full">
                                <option value="">Semua</option>
                                <option value="publish">Publish</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <!-- Tombol Tambah -->
                        <a href="/admin/berita/tambahberita" 
                            class="flex items-center gap-2 bg-green-600 text-gray-50 px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition">
                            <i class="fa-solid fa-plus"></i>
                            Tambah Berita
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg shadow-md mx-6">
                <table class="min-w-full w-[1010px] bg-white border border-gray-300 text-sm">
                    <thead class="bg-[#CCCACA] font-bold text-base">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold font-roboto">No</th>
                        <th class="px-4 py-3 text-left font-semibold font-roboto">Gambar</th>
                        <th class="px-4 py-3 text-left font-semibold font-roboto">Judul</th>
                        <th class="px-4 py-3 text-left font-semibold font-roboto">Isi</th>
                        <th class="px-4 py-3 text-left font-semibold font-roboto">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold font-roboto">Status</th>
                        <th class="px-4 py-3 text-left font-semibold font-roboto">Penulis</th>
                        <th class="px-4 py-3 text-left font-semibold font-roboto ">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($beritas as $index => $berita)
                    <tr id="beritaRow" class="all-row border-b hover:bg-gray-100"
                    x-show="
                                (
                                    '{{ strtolower($berita->judul) }}'.includes(search.toLowerCase())
                                )
                                &&
                                (
                                    filter === '' ||
                                    filter === '{{ strtolower($berita->status) }}'
                                )
                            ">
                        <td class="px-4 py-3 font-roboto tracking-wide text-base ">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-roboto tracking-wide text-base">
                            <img src="{{ asset('storage/' . $berita->gambar) }}"  class="w-20 h-20 object-cover">
                        </td>
                        <td class="px-4 py-3 font-roboto tracking-wide text-base max-w-xs">{{ $berita->judul }}</td>
                        <td class="px-4 py-3 font-roboto tracking-wide text-base">{!! Str::limit($berita->isi, 100) !!}</td>
                        <td class="px-4 py-3 font-roboto tracking-wide text-base">{{ $berita->tanggal }}</td>
                        <td class="px-4 py-3 font-roboto tracking-wide text-base">{{ ucfirst($berita->status) }}</td>
                        <td class="px-4 py-3 font-roboto tracking-wide text-base">{{ $berita->penulis }}</td>
                        <td class="px-4 py-3 font-roboto tracking-wide text-base text-center">
                            <div class="flex justify-center items-center gap-2 w-full">
                                <a href="/admin/berita/editberita/{{ $berita->id }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form id="delete-form-{{ $berita->id }}" action="{{ route('admin.keloladokumen.delete', $berita->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="openDeleteModal('{{ $berita->id }}', '{{ $berita->judul }}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    <tr x-show="showNoResults">
                        <td colspan="6" class="px-6 py-3 font-roboto tracking-wide text-base text-center">
                            Berita tidak ditemukan
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
            Apakah Anda yakin ingin menghapus pengguna
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