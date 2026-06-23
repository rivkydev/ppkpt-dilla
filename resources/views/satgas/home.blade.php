<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Investigasi - PPKPT ITH</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
</head>
<body>
    <x-nav-baar></x-nav-baar>
    
    <div class="flex mt-31">
        <div class="h-[520px] w-[300px] bg-[#0970A5] px-4 py-15 shadow-lg rounded-lg lg:block hidden">
            <x-sidebarr :active="'beranda'"></x-sidebarr>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] lg:h-[520px] h-screen p-5 overflow-y-auto">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-[#0970A5] pl-3">Dashboard Satgas</h2>
                    <p class="text-gray-600 mt-1">Ringkasan status penanganan pelaporan oleh Satgas.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-[#0970A5] hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-semibold mb-1">Total Ditugaskan</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $totalAduanDitugaskan }}</h3>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full text-[#0970A5] w-12 h-12 flex items-center justify-center">
                                <i class="fa-solid fa-list-check text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-yellow-500 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-semibold mb-1">Sedang Diinvestigasi</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $sedangInvestigasi }}</h3>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full text-yellow-600 w-12 h-12 flex items-center justify-center">
                                <i class="fa-solid fa-spinner text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-green-500 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-semibold mb-1">Aduan Selesai</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $aduanSelesai }}</h3>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full text-green-600 w-12 h-12 flex items-center justify-center">
                                <i class="fa-solid fa-check-double text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Aduan Terbaru (Ditugaskan)</h3>
                        <a href="{{ route('satgas.laporanditangani') }}" class="text-sm text-[#0970A5] hover:underline font-medium">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-sm border-b">
                                    <th class="px-6 py-3 font-medium">KODE ADUAN</th>
                                    <th class="px-6 py-3 font-medium">TANGGAL PERISTIWA</th>
                                    <th class="px-6 py-3 font-medium">KATEGORI</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                @forelse($aduanTerbaru as $aduan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $aduan->kode_aduan }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $aduan->tanggal_peristiwa ? \Carbon\Carbon::parse($aduan->tanggal_peristiwa)->format('d M Y') : '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-xs">{{ $aduan->category }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada aduan yang ditugaskan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>