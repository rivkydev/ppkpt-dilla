<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{config('app.name')}}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link
      rel="stylesheet"  
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
  </head>
<body>
    <x-nav-baar></x-nav-baar>

    <div class="max-w-full mx-20 bg-white rounded-2xl shadow-lg mt-30 mb-2">

        <!-- Header -->
        <div class="px-6 py-4 relative w-full flex items-center">
            <a href="javascript:history.back()"
                class="absolute left-8 flex items-center justify-center
                    w-9 h-9 rounded-full bg-[#F08619] text-white">
                <i class="fa-solid fa-angle-left"></i>
            </a>

            <h5
                class="font-bold tracking-widest bg-[#F08619] text-gray-50 text-center rounded-xl w-full py-3 text-xl shadow-md">
                Hasil Investigasi
            </h5>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mx-6 bg-gray-100 p-8 rounded-xl">

            <!-- KIRI -->
            <div class="space-y-6">

                <!-- Informasi Aduan -->
                <div class="card bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-2">
                        <div class="w-1 h-6 bg-[#F08619] rounded-full mr-3"></div>
                        <h2 class="text-lg font-semibold text-gray-800">Informasi Aduan</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between border-b border-gray-100">
                            <span class="font-medium text-gray-600">Hasil Akhir</span>
                            <span class="font-semibold">{{ $investigasi->hasil_akhir }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100">
                            <span class="font-medium text-gray-600">Tanggal</span>
                            <span class="text-gray-700">{{ $investigasi->tanggal }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Kategori</span>
                            <span class="text-gray-700 text-right">{{ $aduan->category }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tindak Lanjut -->
                <div class="card bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-3">
                        <div class="w-1 h-6 bg-blue-500 rounded-full mr-3"></div>
                        <h2 class="text-lg font-semibold text-gray-800">Tindak Lanjut</h2>
                    </div>

                    @php
                        $tindakLanjut = json_decode($investigasi->tindak_lanjut, true);
                    @endphp

                    <ul class="space-y-2 text-gray-600 list-disc list-inside">
                        @forelse ($tindakLanjut as $item)
                            <li>{{ $item }}</li>
                        @empty
                            <li class="italic text-gray-400">Belum ada tindak lanjut</li>
                        @endforelse
                    </ul>

                </div>

                <!-- Kronologi -->
                <div class="card bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-2">
                        <div class="w-1 h-6 bg-[#F08619] rounded-full mr-3"></div>
                        <h2 class="text-lg font-semibold text-gray-800">Kronologi</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $investigasi->kronologi }}
                    </p>
                </div>

            </div>

            <!-- KANAN -->
            <div class="space-y-6">

                <!-- Fakta Terbukti -->
                <div class="card bg-green-50 rounded-xl p-6 shadow-sm border border-green-100">
                    <div class="flex items-center mb-2">
                        <div class="w-1 h-6 bg-green-500 rounded-full mr-3"></div>
                        <h2 class="text-lg font-semibold text-green-800">Fakta Terbukti</h2>
                    </div>
                    <div class="space-y-3">
                        <span class="text-green-700">{{ $investigasi->fakta_terbukti }}</span>
                    </div>
                </div>

                <!-- Fakta Tidak Terbukti -->
                <div class="card bg-red-50 rounded-xl p-6 shadow-sm border border-red-100">
                    <div class="flex items-center mb-2">
                        <div class="w-1 h-6 bg-red-500 rounded-full mr-3"></div>
                        <h2 class="text-lg font-semibold text-red-800">Fakta Tidak Terbukti</h2>
                    </div>
                    <div class="space-y-3">
                        <span class="text-red-700">{{ $investigasi->fakta_tidak_terbukti }}</span>
                    </div>
                </div>

                <!-- Kesimpulan -->
                <div class="card bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-2">
                        <div class="w-1 h-6 bg-purple-500 rounded-full mr-3"></div>
                        <h2 class="text-lg font-semibold text-gray-800">Kesimpulan</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $investigasi->kesimpulan }}
                    </p>
                </div>

            </div>

        </div>

    </div>


</body>
</html>