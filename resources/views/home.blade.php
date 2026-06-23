<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{config('app.name')}}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    
    <x-nav-barr>
        <x-slot name="label1">Beranda</x-slot>
        <x-slot name="label2">Berita</x-slot>
        <x-slot name="label3">Tentang Kami</x-slot>
    </x-nav-barr>

    <!-- Bagian Beranda -->
    <section id="beranda"
        class="m-3 mt-28 bg-cover bg-center bg-no-repeat rounded-lg px-4 sm:px-6 md:px-10 py-10"
        style="background-image: url('img/section1.png')">
            <div class="mx-auto max-w-2xl py-16 sm:py-12 lg:max-w-none lg:py-32">
                <div class="lg:mt-6 space-y-12 lg:grid lg:grid-cols-2 lg:space-y-0 lg:gap-x-6">
                    <div>
                        <h1 class="font-bold text-gray-50 tracking-widest lg:text-5xl md:text-5xl text-4xl text-justify lg:w-100 md:w-100 mx-auto">LAPOR AMAN</h1>
                        <h4 class="font-semibold text-[#F08619] tracking-widest lg:text-xl md:text-xl text-lg text-justify lg:w-100 md:w-100 mx-auto">INSTITUT TEKNOLOGI B.J. HABIBIE</h4>
                        <p class="text-gray-50 text-justify lg:w-100 md:w-100 mx-auto lg:text-xl md:text-xl text-lg">Sejak tahun 2022, ITH telah memulai menyusun kebijakan pencegahan dan penanganan pelecehan seksual melalui surat keputusan Rektor ITH tentang pedoman pencegahan pelecehan seksual di lingkungan ITH.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-[#F08619] tracking-wider text-xl text-center">Jangan Takut Untuk Laporkan 
                        <br>Kami Membersamai Anda</h4>
                        <a href="/login" class="flex items-center gap-6 font-rubik font-medium tracking-wider bg-[#F08619] text-white text-lg px-10 py-4 rounded-full w-60 mx-auto mt-4 hover:bg-[#3B6BA2]" ><i class="fa-solid fa-envelope"></i>Buat Aduan</a>
                        <div x-data="{ hasil: {{ request('ppkpt') ? 'true' : 'false' }} }">
                            <button @click="hasil = !hasil" class="cursor-pointer flex items-center gap-6 font-rubik font-medium tracking-wider bg-transparent outline-solid text-gray-50 text-lg px-10 py-4 rounded-full w-60 mx-auto mt-4 hover:bg-[#3B6BA2]" ><i class="fa-regular fa-bell"></i> Hasil Aduan</button>
                            <div x-show="hasil">
                            <div class="bg-[#000000]/50 absolute top-0 left-0 right-0 bottom-0 lg:h-[1900px] md:h-[2820px] sm:h-[2730px] h-[2830px] lg:p-50 md:px-25 px-10 py-50 z-40">
                                <div class="bg-white w-auto h-auto items-center justify-center p-4 rounded-lg">
                                    <h1 class="relative bg-[#F08619] text-white py-2 text-center font-roboto rounded-lg font-semibold tracking-wider">
                                        <i @click="hasil = false" class="fa-solid fa-angle-left absolute left-4 top-1/2 -translate-y-1/2 cursor-pointer"></i>
                                        Hasil Aduan
                                    </h1>  
                                    <div class="bg-[#E0DEDE] mt-2 w-full h-auto flex lg:flex-col flex-col md:flex-row items-center justify-center">
                                    <form method="GET" class="flex items-center m-2">
                                        <input type="hidden" name="ppkpt" value="1">
                                        <input type="text" name="kode" placeholder="Masukkan Kode Aduan" value="{{ request('kode') }}" 
                                            class="bg-white px-6 py-2 rounded-l-lg focus:outline-none focus:ring-1 focus:ring-[#F08619]">
                                        <button type="submit" 
                                                class="bg-[#F08619] text-white px-6 py-2 rounded-r-lg hover:bg-[#3B6BA2]">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            Cari
                                        </button>
                                    </form>
                                    <div class="items-center justify-center flex flex-col" x-data="{ selectedAduan: null }">
                                        @if(request('kode') && !$aduan)
                                            <div class="p-5 px-10 m-2 rounded-lg">
                                                <p class="text-red-500 italic text-center">Kode Aduan tidak ditemukan</p>
                                            </div>
                                        @elseif($aduan)
                                            <div 
                                                class="bg-white p-5 px-10 m-2 rounded-lg flex flex-row items-center justify-center gap-2 cursor-pointer hover:bg-gray-50"
                                                @click="selectAduan({{ $aduan->id }})" {{-- klik untuk pilih aduan --}}
                                            >
                                                <i class="fa-solid fa-file-circle-check text-[#F08619] text-5xl"></i>
                                                <div class="flex flex-col">
                                                    <h3 class="font-bold">{{ $aduan->kode_aduan }}</h3>
                                                    <p class="text-gray-500">{{ $aduan->created_at->translatedFormat('d F Y') }}</p>
                                                </div>  
                                            </div>    
                                        @else
                                            <div class="p-5 px-10 m-2 rounded-lg">
                                                <p class="text-gray-500 italic text-center">Masukkan kode Aduan untuk melihat status</p>
                                            </div>
                                        @endif

                                        {{-- Bagian detail status --}}
                                        <div class="mt-2">
                                            <div class="relative border-l-4 border-[#F08619] pl-2 space-y-6 my-6 ">
                                                <div x-show="!selectedAduan" class="text-gray-500 italic">
                                                   
                                                </div>

                                                @forelse ($aduans as $aduan)
                                                    <div x-show="selectedAduan === {{ $aduan->id }}" x-transition>
                                                        @php
                                                            $steps = [];
                                                            $status = null;

                                                            if ($aduan->statuses) {
                                                                if (is_iterable($aduan->statuses)) {
                                                                    $status = $aduan->statuses->first();
                                                                } else {
                                                                    $status = $aduan->statuses;
                                                                }
                                                            }

                                                            if ($status) {
                                                                $steps = [
                                                                    ['label' => $status->label5 ?? null, 'value' => $status->status5 ?? null],
                                                                    ['label' => $status->label4 ?? null, 'value' => $status->status4 ?? null],
                                                                    ['label' => $status->label3 ?? null, 'value' => $status->status3 ?? null],
                                                                    ['label' => $status->label2 ?? null, 'value' => $status->status2 ?? null],
                                                                    ['label' => $status->label1 ?? null, 'value' => $status->status1 ?? null],
                                                                ];
                                                                $steps = array_filter($steps, fn($s) => !empty($s['value']));
                                                            }
                                                        @endphp

                                                        @if(empty($steps))
                                                            <p class="text-gray-500 italic">Belum ada status untuk aduan ini.</p>
                                                        @else
                                                            @foreach ($steps as $step)
                                                                <div class="relative flex gap-2 items-center w-[600px]">
                                                                    <i class="fa-solid fa-circle-check {{ $loop->first ? 'text-[#1E8535]' : 'text-gray-500' }}"></i>
                                                                    <div>
                                                                        <h4 class="font-semibold {{ $loop->first ? 'text-[#1E8535]' : 'text-gray-500' }}">
                                                                            {{ $step['label'] }}
                                                                        </h4>
                                                                        <p class="text-sm {{ $loop->first ? 'text-gray-600' : 'text-gray-500' }}">
                                                                            {!! $step['value'] !!}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @empty
                                                @endforelse

                                                <!-- Popup -->
                                                <div id="penolakan-{{ isset($aduan) && $aduan ? $aduan->id : 'default' }}" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 target:flex">
                                                <div class="bg-white p-6 rounded-lg w-[90%] md:w-[600px] relative">
                                                    <h2 class="text-lg font-bold text-[#F08619] mb-2">Detail Penolakan</h2>
                                                    <div class="text-gray-700 mb-4 text-justify">
                                                    Kepada Yth.  
                                                    <br>
                                                    @if(isset($aduan) && $aduan)
                                                        {{ $aduan->nama_pelapor ?? 'Nama tidak tersedia' }}
                                                    <p>Berdasarkan hasil pemeriksaan awal terhadap laporan yang Anda kirimkan,  
                                                        kami informasikan bahwa laporan tersebut <strong>belum dapat diproses lebih lanjut</strong> karena data yang diberikan tidak sesuai dengan ketentuan pelaporan.  
                                                        </p>
                                                    <p>Alasan penolakan: <br>
                                                    @if(isset($aduan) && $aduan && $aduan->statuses && $aduan->statuses->first())
                                                        {{ $aduan->statuses->first()->penolakan ?? 'Alasan penolakan tidak tersedia' }}
                                                    @endif
                                                    </p>
                                                    <p>Anda dapat mengirimkan kembali laporan baru dengan memperbaiki data tersebut.  
                                                    Terima kasih atas perhatian dan kerja samanya.  
                                                    </p> <br>
                                                    <p>Hormat kami, <br>
                                                    <strong>Admin PPKPT ITH</strong></p>
                                                    @endif
                                                    </div>


                                                    <a href="#" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                                                    <i class="fa-solid fa-xmark text-xl"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>  
    <section id="berita" class="m-3 bg-cover bg-center bg-no-repeat rounded-lg px-4 sm:px-6 md:px-10 py-10 scroll-mt-28"
    style="background-image: url('img/section2.png')">
    <x-berita :beritas="$beritas"></x-berita>
    </section>
    <section id="tentang"
    class="m-3 bg-cover bg-center bg-no-repeat rounded-lg px-4 sm:px-6 md:px-10 py-10 scroll-mt-28"
    style="background-image: url('img/section3.png')">
        <x-tentangkami></x-tentangkami>
    </section>
    <div class="text-center p-2 font-roboto tracking-wider text-base">Hak Cipta © Institut Teknologi Bacharuddin Jusuf Habibie</div>
    </div>
    <x-faq></x-faq>
</body>
</html>
