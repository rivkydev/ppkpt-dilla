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
<body class="bg-gray-100">
    <x-nav-baar></x-nav-baar>
    
    <div class="flex mt-31">
        <div class="h-[520px] w-[300px] bg-[#0970A5] px-4 py-15 shadow-lg rounded-lg lg:block hidden">
            <x-sidebarr :active="''"></x-sidebarr>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] lg:h-[520px] h-screen p-2 overflow-y-auto">
                <div class="relative px-6 py-4">
                    <h5 class="font-bold tracking-widest 
                                bg-[#0970A5] text-gray-50 
                                rounded-xl w-full py-3 text-xl 
                                text-center shadow-md">
                            DETAIL INVESTIGASI
                    </h5>
                    <a href="#"
   class="btn-back absolute left-10 top-1/2 -translate-y-1/2 text-white text-lg">
    <i class="fa-solid fa-angle-left"></i>
</a>
                </div>
                
                <!-- Main Content -->
                <div class="px-4 py-4 space-y-6">

                    <div class="bg-white rounded-xl shadow-md p-4">
                        <p><span class="font-medium">Ditangani Oleh :</span> {{ $status->userPenerima->fullname ?? '-' }}</p>
                    </div>
                    <!-- Informasi Aduan -->
                    <div class="bg-white rounded-xl shadow-md p-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Aduan</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Kode Aduan:</span> {{ $investigasi->kode_aduan }}</p>
                            <p><span class="font-medium">Jenis Kekerasan:</span> {{ $investigasi->jenis_kekerasan }}</p>
                            <p><span class="font-medium">Lokasi Kejadian:</span> {{ $investigasi->lokasi_kejadian }}</p>
                            <p><span class="font-medium">Tanggal Investigasi:</span> {{ \Carbon\Carbon::parse($investigasi->tanggal)->format('d F Y') }}</p>
                            <a href="{{ route('satgas.detaillaporan', $investigasi->kode_aduan) }}" class="inline-block mt-2 text-white bg-[#0970A5] hover:bg-[#065a84] px-4 py-2 rounded-lg font-medium transition">Lihat Detail</a>
                        </div>
                    </div>

                    <!-- Pihak Terkait -->
                    <div class="bg-white rounded-xl shadow-md p-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Pihak Terkait</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Nama Korban:</span> {{ $investigasi->nama_korban }}</p>
                            <p><span class="font-medium">Status Korban:</span> {{ $investigasi->status_korban }}</p>
                            <p><span class="font-medium">Nama Terlapor:</span> {{ $investigasi->nama_terlapor }}</p>
                            <p><span class="font-medium">Status Terlapor:</span> {{ $investigasi->status_terlapor }}</p>
                            <p><span class="font-medium">Saksi:</span> {{ $investigasi->nama_saksi}}</p>
                            <p><span class="font-medium">Keterangan Saksi:</span> {{ $investigasi->keterangan_saksi }}</p>
                        </div>
                    </div>

                    <!-- Proses Investigasi -->
                    <div class="bg-white rounded-xl shadow-md p-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Proses Investigasi</h3>
                        @if($investigasi->proses)
                            <div class="grid grid-cols-1 gap-2 mb-4">
                                @foreach(json_decode($investigasi->proses) as $proses)
                                    <div class="flex items-center gap-2 bg-gray-50 p-2 rounded">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        <span>{{ ucwords(str_replace('_', ' ', $proses)) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if($investigasi->catatan_proses)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-1">Catatan Proses:</h4>
                                <p class="text-gray-600">{{ $investigasi->catatan_proses }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Temuan Investigasi -->
                    <div class="bg-white rounded-xl shadow-md p-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Temuan Investigasi</h3>
                        <div class="space-y-4">
                            @if($investigasi->kronologi)
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-1">Kronologi:</h4>
                                    <p class="text-gray-600 whitespace-pre-line bg-gray-50 p-3 rounded">{{ $investigasi->kronologi }}</p>
                                </div>
                            @endif

                            @if($investigasi->fakta_terbukti)
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-1">Fakta Terbukti:</h4>
                                    <p class="text-gray-600 whitespace-pre-line bg-gray-50 p-3 rounded">{{ $investigasi->fakta_terbukti }}</p>
                                </div>
                            @endif

                            @if($investigasi->fakta_tidak_terbukti)
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-1">Fakta Tidak Terbukti:</h4>
                                    <p class="text-gray-600 whitespace-pre-line bg-gray-50 p-3 rounded">{{ $investigasi->fakta_tidak_terbukti }}</p>
                                </div>
                            @endif

                            @if($investigasi->file_terbukti)
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2">Lampiran Bukti:</h4>
                                    <a href="{{ asset('storage/' . $investigasi->file_terbukti) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-2 text-[#0970A5] hover:underline">
                                        <i class="fas fa-file-alt"></i> Lihat Dokumen
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Hasil & Tindak Lanjut -->
                    <div class="bg-white rounded-xl shadow-md p-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Hasil & Tindak Lanjut</h3>
                        <div class="space-y-4">
                            @if($investigasi->tindak_lanjut)
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2">Tindak Lanjut:</h4>
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach(json_decode($investigasi->tindak_lanjut) as $tindak)
                                            <div class="bg-blue-50 text-blue-700 px-3 py-2 rounded-lg">
                                                <i class="fas fa-arrow-right text-xs mr-2"></i>
                                                {{ ucwords(str_replace('_', ' ', $tindak)) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($investigasi->catatan_tindak_lanjut)
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-1">Catatan Tindak Lanjut:</h4>
                                    <p class="text-gray-600 whitespace-pre-line bg-gray-50 p-3 rounded">{{ $investigasi->catatan_tindak_lanjut }}</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-1">Hasil Akhir:</h4>
                                    @php
                                        $statusClass = [
                                            'terbukti' => 'bg-red-100 text-red-800',
                                            'sebagian_terbukti' => 'bg-yellow-100 text-yellow-800',
                                            'tidak_terbukti' => 'bg-green-100 text-green-800'
                                        ][$investigasi->hasil_akhir] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                        {{ ucwords(str_replace('_', ' ', $investigasi->hasil_akhir)) }}
                                    </span>
                                </div>
                            </div>

                            @if($investigasi->kesimpulan)
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-1">Kesimpulan:</h4>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-gray-600 whitespace-pre-line">{{ $investigasi->kesimpulan }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>