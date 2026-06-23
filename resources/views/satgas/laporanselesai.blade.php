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
</head>
<body class="h-screen lg:overflow-y-hidden" x-data="allPage">
    <x-nav-baar></x-nav-baar>
    <div class="flex mt-31">
        <div class="h-screen w-[300px] bg-[#0970A5] px-4 py-15 shadow-lg rounded-lg lg:block hidden">
            <x-sidebarr :active="'laporan-selesai'"></x-sidebarr>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] lg:md:h-[calc(100vh-120px)] p-2 overflow-y-auto">
                <div class="px-6 py-4">
                    <!-- Judul -->
                    <h5 class="font-bold tracking-widest bg-[#0970A5] text-gray-50 text-center rounded-xl w-full py-3 text-xl flex items-center justify-center shadow-md">
                        LAPORAN SELESAI
                    </h5>

                    <!-- Controls -->
                    <div class="flex flex-col md:flex-row gap-1 mt-6 items-center">
                        <!-- Pencarian -->
                        <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-2 bg-white shadow-sm w-full md:w-auto">
                            <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                            <input
                                type="text"
                                placeholder="Cari Kode Aduan..."
                                class="outline-none w-full"
                                id="searchInput"
                                x-model="search">
                        </div>

                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg shadow-md mx-6">
                <table class="min-w-full bg-white border border-gray-300 text-sm">
                    <thead class="bg-[#CCCACA] font-bold text-base">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">No</th>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">Kode Aduan</th>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">Jenis Kekerasan</th>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">Tanggal Laporan</th>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">Status Penanganan</th>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">Ditangani</th>
                        <th class="px-6 py-3 text-left font-semibold font-roboto">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @forelse ($aduans as $index => $aduan)
                        <tr class="hover:bg-gray-50 transition all-row"
                        x-show="
        (
            '{{ strtolower($aduan->kode_aduan) }}'.includes(search.toLowerCase())
        )">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800 kodeAduan">{{ $aduan->kode_aduan ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $aduan->category ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $aduan->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $aduan->statuses->last()->label4 ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $aduan->statuses->last()->userPenerima->fullname ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('satgas.detailinvestigasi', $aduan->kode_aduan) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#0970A5] rounded-lg hover:bg-[#065a84] transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-gray-500 italic">
                                Belum ada laporan yang sedang selesai
                            </td>
                        </tr>
                    @endforelse
                    <tr x-show="showNoResults">
                            <td colspan="6" class="px-6 py-6 text-center text-gray-500 italic">
                                Laporan tidak ditemukan
                            </td>
                        </tr>
                </tbody>

                    
                </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>