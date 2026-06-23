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
            <x-sidebarr :active="''"></x-sidebarr>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] lg:h-[520px] h-screen p-5 overflow-y-auto">
                <div class="relative mb-6">
                    <h5 class="font-bold tracking-widest 
                                bg-[#0970A5] text-gray-50 
                                rounded-xl w-full py-3 text-xl 
                                text-center shadow-md">
                            DETAIL ADUAN
                    </h5>
                    <a href="#"
                    class="btn-back absolute left-4 top-1/2 -translate-y-1/2 text-white text-lg">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                </div>
            
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-semibold text-[#0970A5]
                                    border-l-4 border-[#0970A5] pl-3">
                                Informasi Aduan
                            </h2>
                            <div class="flex items-center gap-2">
    @php
        $total = $aduan->count();

        // hitung rasio ranking
        $ratio = 1 - (($aduan->peringkat - 1) / max($total - 1, 1));

        // warna merah ke hijau (lebih enak dilihat)
        $red   = intval(200 * $ratio);
        $green = intval(90 + (130 * (1 - $ratio)));
        $blue  = 80;

        $warna = "background-color: rgb($red, $green, $blue); color: white;";
    @endphp

    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-700">
        {{ $aduan->bersedia ?? '-' }}
    </span>

    <span 
        class="px-3 py-1 text-sm font-semibold rounded-full"
        style="{{ $warna }}">
        {{ ucfirst($aduan->prioritas) ?? '-' }}
    </span>
</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                            <div>
                                <p class="text-gray-500">Kode Aduan</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->kode_aduan ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Kategori</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->category ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Tanggal Peristiwa</p>
                                <p class="font-semibold text-gray-800">
                                    {{ $aduan->tanggal_peristiwa ? \Carbon\Carbon::parse($aduan->tanggal_peristiwa)->format('d F Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Lokasi Kejadian</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->lokasi ?? '-'}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h2 class="text-base font-semibold text-[#0970A5] mb-4 border-l-4 border-[#0970A5] pl-3">
                            Data Pelapor
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                            <div>
                                <p class="text-gray-500">Nama Pelapor</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->nama_pelapor ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Alamat</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->alamat_pelapor ?? '-'}}</</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Email</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->email_pelapor ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">No HP</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->phone_pelapor ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Bersedia Dihubungi</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->hubungi ?? '-'}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h2 class="text-base font-semibold text-[#0970A5] mb-4 border-l-4 border-[#0970A5] pl-3">
                            Data Korban
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                            <div>
                                <p class="text-gray-500">Nama Korban</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->nama_korban ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Jenis Kelamin</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->jenis_kelamin_korban ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Alamat</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->alamat_korban ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">No HP</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->phone_korban ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Status Korban</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->status_korban ?? '-'}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h2 class="text-base font-semibold text-[#0970A5] mb-4 border-l-4 border-[#0970A5] pl-3">
                            Data Terlapor
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                            <div>
                                <p class="text-gray-500">Nama Terlapor</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->nama_terlapor ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Jenis Kelamin</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->jenis_kelamin_terlapor ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Alamat</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->alamat_terlapor ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">No HP</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->phone_terlapor ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Status</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->status_terlapor ?? '-'}}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Karakteristik</p>
                                <p class="font-semibold text-gray-800">{{ $aduan->karakteristik_terlapor ?? '-'}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h2 class="text-base font-semibold text-[#0970A5] mb-4 border-l-4 border-[#0970A5] pl-3">
                            Kronologi
                        </h2>

                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="font-medium text-gray-700 mb-1">Kronologi</p>
                                <div class="bg-gray-50 border rounded-lg p-3 text-gray-700">
                                    {{ $aduan->chronology ?? '-'}}
                                </div>
                            </div>

                        </div>
                    </div>                   
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h2 class="text-base font-semibold text-[#0970A5] mb-4 border-l-4 border-[#0970A5] pl-3">
                            Dokumen Pendukung
                        </h2>

                        <div class="flex gap-4">
                            @if ($aduan->pernyataan_pelapor)
                                <a href="{{ asset('storage/' . $aduan->pernyataan_pelapor) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border rounded-lg hover:bg-gray-100 text-sm">
                                    <i class="fa-solid fa-file-pdf text-red-500"></i>
                                    File Pernyataan
                                </a>
                            @endif
                            @if ($aduan->bukti_pelaporan)
                                <a href="{{ asset('storage/' . $aduan->bukti_pelaporan) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border rounded-lg hover:bg-gray-100 text-sm">
                                    <i class="fa-solid fa-file-pdf text-red-500"></i>
                                    File Bukti
                                </a>
                            @endif
                            @if (!$aduan->pernyataan_pelapor && !$aduan->bukti_pelaporan)
                                <p class="text-sm text-gray-500">Tidak ada dokumen pendukung.</p>
                            @endif
                        </div>
                    </div>                   
                    
                    <!-- Tombol Terima Aduan -->
             @php
                $latestStatus = $aduan->statuses->first();
            @endphp

            @if(!$latestStatus || $latestStatus->diterima_oleh === null)
                            <form action="{{ route('satgas.terimaaduan', $aduan->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2
                    px-6 py-2.5 rounded-lg
                    bg-[#F08619] text-white
                    font-semibold
                    hover:bg-[#0970A5]
                    shadow-md hover:shadow-lg
                    transition"
            class="inline-flex items-center gap-2
                    px-6 py-2.5 rounded-lg
                    bg-[#F08619] text-white
                    transition">
                <i class="fa-solid fa-check"></i>
                Terima Aduan
                </button>
            </form>
            @endif

            </div>
            
        </div>
    </div>
</body>
</html>