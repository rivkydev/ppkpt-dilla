<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PPKPT ITH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
</head>

<body x-data="aduanPage">

    <x-nav-baar></x-nav-baar>
    <div class="flex mt-31">
        <!-- Sidebar -->
        <div class="w-[300px] bg-[#F08619] px-4 py-15 shadow-lg rounded-lg lg:block hidden">
            <x-sidebar :active="'formulir'"></x-sidebar>
            <div class="grid grid-cols-1 lg:grid-cols-3 
gap-3 lg:gap-4 
px-2 md:px-4 
h-auto lg:h-[calc(100vh-120px)] 
w-full">




    <!-- KOLOM LIST ADUAN -->
<div class="bg-[#E0DEDE] rounded-lg shadow-lg
h-[40vh] lg:h-full
flex flex-col
overflow-hidden">


        <!-- Pencarian -->
        <div class="px-4 pt-4 pb-3 sticky top-0 z-10 bg-[#E0DEDE]">
            <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-2 bg-white shadow-sm w-full">
                <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                <input type="text"
                       placeholder="Kode Aduan..."
                       class="outline-none w-full text-sm"
                       id="searchInput"
                       x-model = "search">
            </div>
        </div>

        <!-- List Aduan -->
        <div class="px-4 pb-4 overflow-y-auto flex-1">
            <div class="flex flex-col gap-2 pt-2">
                @forelse($aduans as $aduan)
                    <div
                     data-search="{{ strtolower($aduan->kode_aduan . ' ' . $aduan->created_at->translatedFormat('d F Y')) }}"
    x-show="$el.dataset.search.includes(search.toLowerCase())"
    @click="selectAduan({{ $aduan->id }})"
    :class="selectedAduan === {{ $aduan->id }}
        ? 'bg-[#F08619] text-white'
        : 'bg-white'"
    class="complaint-item w-full block flex items-start gap-3
           p-3 rounded-lg
           cursor-pointer
           transition-all duration-200
           hover:shadow-md
           box-border">

    <i class="fa-solid fa-file-circle-check text-3xl flex-shrink-0"
       :class="selectedAduan === {{ $aduan->id }}
           ? 'text-white'
           : 'text-[#F08619]'"></i>

    <div class="w-full overflow-hidden">
        <h1 class="font-bold text-md truncate">
            {{ $aduan->kode_aduan }}
        </h1>

        <p class="text-sm truncate">
            {{ $aduan->created_at->translatedFormat('d F Y') }}
        </p>
    </div>

</div>

                @empty
                    <div class="text-center py-4 text-gray-500">
                        Tidak ada data aduan
                    </div>
                @endforelse
            </div>
        </div>

    </div>


    <!-- KOLOM DETAIL ADUAN -->
    <div class="bg-[#E0DEDE] rounded-lg shadow-lg md:col-span-2 md:lg:h-[calc(100vh-120px)] lg:overflow-y-auto px-4 py-4 mt-4 md:mt-0 ">

        <div class="pb-4">

@if(session('success'))
<div id="toast" class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    <i class="fas fa-check-circle mr-3"></i> {{ session('success') }}
</div>
@endif


                               <template x-if="selectedAduan">
                        <div>

                            <!-- DETAIL ADUAN -->
                            @foreach($aduans as $aduan)
                                <div x-show="selectedAduan === {{ $aduan->id }}">
                                        <p class="font-bold tracking-wider text-[#F08619]">IDENTITAS PELAPOR</p>
                                        <table class="w-full table-fixed border-collapse">
                                            <tbody>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Nama Pelapor</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->nama_pelapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Alamat</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->alamat_pelapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">File Pernyataan</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span class="text-gray-500 italic">Akses Dibatasi</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Email</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->email_pelapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">No. HP</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->phone_pelapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold">Hubungi</td>  
                                                    <td class="px-2">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->hubungi ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <p class="font-bold tracking-wider text-[#F08619] mt-4">IDENTITAS KORBAN</p>
                                        <table class="w-full table-fixed border-collapse">
                                            <tbody>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Nama Korban</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->nama_korban ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Jenis Kelamin</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->jenis_kelamin_korban ?? '-' }}</span>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Alamat</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->alamat_korban ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">No. HP</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->phone_korban ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Status Di Kampus</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->status_korban ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <p class="font-bold tracking-wider text-[#F08619] mt-4">IDENTITAS TERLAPOR</p>
                                        <table class="w-full table-fixed border-collapse">
                                            <tbody>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Nama Terlapor</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->nama_terlapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Jenis Kelamin</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->jenis_kelamin_terlapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Alamat</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->alamat_terlapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">No. HP</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->phone_terlapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Status Di Kampus</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->status_terlapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Karakteristik</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->karakteristik_terlapor ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Peringatan</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->warning_detail ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <p class="font-bold tracking-wider text-[#F08619] mt-4">PERISTIWA</p>
                                        <table class="w-full table-fixed border-collapse">
                                            <tbody>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Tanggal Peristiwa</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        {{ $aduan->tanggal_peristiwa ? \Carbon\Carbon::parse($aduan->tanggal_peristiwa)->translatedFormat('d F Y') : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Kategori</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td>{{ $aduan->category ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Kronologi Peristiwa</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->chronology ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">File Bukti</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span class="text-gray-500 italic">Akses Dibatasi</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold w-[140px] align-top">Lokasi</td>
                                                    <td class="px-2 w-4">:</td>
                                                    <td class="w-full break-words whitespace-normal">
                                                        <span>{{ $aduan->lokasi ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <!-- Tombol Awal -->
                                        <div x-show="!showRejectForm" class="flex gap-2 mt-4">
                                            <form :id="'form-kirim-' + {{ $aduan->id }}"
                                                action="{{ route('admin.kirimKeSatgas', $aduan->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                            </form>
                                            <button
                                                @click="openConfirmModal({{ $aduan->id }})"
                                                class="bg-[#F08619] text-white px-4 py-2 rounded-lg hover:bg-[#0970A5]">
                                                Kirim Ke Satgas
                                            </button>
                                            <button @click="showRejectForm = true"
                                                class="bg-[#F08619] text-white px-4 py-2 rounded-lg hover:bg-[#0970A5]">Tolak
                                                Aduan</button>
                                        </div>

                                        <!-- Form Penolakan -->
                                        <div x-show="showRejectForm" class="mt-4">
                                            <form action="{{ route('admin.tolakAduan', $aduan->id) }}"
                                                method="POST">
                                                @csrf
                                                <label class="block font-bold mb-2">Alasan Penolakan:</label>
                                                <textarea name="alasan_penolakan"
                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F08619] resize-none"
                                                    rows="4" placeholder="Masukkan alasan penolakan aduan..."></textarea>
                                                <div class="flex gap-2 mt-3">
                                                    <button type="button" @click="showRejectForm = false"
                                                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Batal</button>
                                                    <button type="submit"
                                                        class="bg-[#F08619] text-white px-4 py-2 rounded-lg hover:bg-[#0970A5]">Kirim</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                            @endforeach

                        </div>
                    </template>


                    <template x-if="!selectedAduan">
                        <div class="text-center py-20 text-gray-500">
                            <p class="text-lg font-semibold">Pilih salah satu aduan untuk melihat detail</p>
                        </div>
                    </template>


        </div>

    </div>

</div>

        </div> 
    </div>

    <!-- Modal Konfirmasi Kirim Ke Satgas -->
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center"
        @click.self="showConfirmModal = false">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4" @click.stop>
            <div class="flex items-center justify-center mb-4">
                <div class="bg-[#F08619] rounded-full p-3">
                    <i class="fas fa-question text-white text-3xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center mb-2">Konfirmasi Pengiriman</h3>
            <p class="text-gray-600 text-center mb-6">
                Apakah Anda yakin ingin mengirim aduan ini ke Satgas?
            </p>
            <div class="flex gap-3 justify-center">
                <button @click="showConfirmModal = false"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </button>
                <button @click="submitForm()"
                    class="px-6 py-2 bg-[#F08619] text-white rounded-lg hover:bg-[#0970A5] transition">
                    Ya, Kirim
                </button>
            </div>
        </div>
    </div>


</body>
</html>
