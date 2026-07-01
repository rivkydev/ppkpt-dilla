<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Investigasi - PPKPT ITH</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
</head>
<body x-data="satgasDecryptPage({{ $aduan->id }})">
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
            
                
                    <div class="mb-6">
                        <button
                            @click="showModal = true"
                            class="bg-blue-500 text-white px-4 py-2 rounded mb-2">
                            Dekripsi Aduan
                        </button>
                        <p class="text-sm text-gray-600" x-show="executionTime">
                            ⏱️ Waktu proses dekripsi: <span x-text="executionTime.toFixed(4)"></span> detik
                        </p>

                        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                            <div class="bg-white p-6 rounded w-96">
                                <h2 class="text-lg font-bold mb-4">Masukkan Key Dekripsi</h2>
                                <input type="password" x-model="key" class="w-full border px-3 py-2 rounded mb-4" placeholder="Masukkan private key" @keyup.enter="decryptAduan()">
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                                    <button @click="decryptAduan()" class="px-4 py-2 bg-green-500 text-white rounded">Dekripsi</button>
                                </div>
                            </div>
                        </div>
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
        <span x-show="!isDecrypted">{{ $aduan->bersedia ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.bersedia : '-'"></span>
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
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->lokasi ?? '-'}}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.lokasi : '-'"></span></p>
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
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->nama_pelapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.nama_pelapor : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Alamat</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->alamat_pelapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.alamat_pelapor : '-'"></span></</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Email</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->email_pelapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.email_pelapor : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">No HP</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->phone_pelapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.phone_pelapor : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Bersedia Dihubungi</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->hubungi ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.hubungi : '-'"></span></p>
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
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->nama_korban ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.nama_korban : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Jenis Kelamin</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->jenis_kelamin_korban ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.jenis_kelamin_korban : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Alamat</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->alamat_korban ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.alamat_korban : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">No HP</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->phone_korban ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.phone_korban : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Status Korban</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->status_korban ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.status_korban : '-'"></span></p>
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
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->nama_terlapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.nama_terlapor : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Jenis Kelamin</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->jenis_kelamin_terlapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.jenis_kelamin_terlapor : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Alamat</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->alamat_terlapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.alamat_terlapor : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">No HP</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->phone_terlapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.phone_terlapor : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Status</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->status_terlapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.status_terlapor : '-'"></span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Karakteristik</p>
                                <p class="font-semibold text-gray-800"><span x-show="!isDecrypted">{{ $aduan->karakteristik_terlapor ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.karakteristik_terlapor : '-'"></span></p>
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
                                    <span x-show="!isDecrypted">{{ $aduan->chronology ?? '-' }}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.chronology : '-'"></span>
                                </div>
                            </div>

                        </div>
                    </div>                   
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h2 class="text-base font-semibold text-[#0970A5] mb-4 border-l-4 border-[#0970A5] pl-3">
                            Dokumen Pendukung
                        </h2>

                        <div class="flex flex-col gap-4">
                            @if ($aduan->pernyataan_pelapor)
                                <div>
                                    <span x-show="!isDecrypted" class="text-sm text-gray-500 italic">Dekripsi aduan untuk mengunduh file pernyataan</span>
                                    <form :action="'{{ route('satgas.downloadFile', ['id' => $aduan->id, 'type' => 'pernyataan']) }}'" method="POST" target="_blank" x-show="isDecrypted" class="inline">
                                        @csrf
                                        <input type="hidden" name="key" :value="key">
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border rounded-lg hover:bg-gray-100 text-sm cursor-pointer">
                                            <i class="fa-solid fa-file-pdf text-red-500"></i>
                                            Download File Pernyataan
                                        </button>
                                    </form>
                                </div>
                            @endif
                            @if ($aduan->bukti_pelaporan)
                                <div>
                                    <span x-show="!isDecrypted" class="text-sm text-gray-500 italic">Dekripsi aduan untuk mengunduh file bukti</span>
                                    <form :action="'{{ route('satgas.downloadFile', ['id' => $aduan->id, 'type' => 'bukti']) }}'" method="POST" target="_blank" x-show="isDecrypted" class="inline">
                                        @csrf
                                        <input type="hidden" name="key" :value="key">
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border rounded-lg hover:bg-gray-100 text-sm cursor-pointer">
                                            <i class="fa-solid fa-file-pdf text-red-500"></i>
                                            Download File Bukti
                                        </button>
                                    </form>
                                </div>
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