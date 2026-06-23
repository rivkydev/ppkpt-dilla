<!doctype html>
<html>
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
    <x-nav-barr>
        <x-slot name="label1">Beranda</x-slot>
        <x-slot name="label2">Berita</x-slot>
        <x-slot name="label3">Tentang Kami</x-slot>
    </x-nav-barr>

    <!-- Bagian Beranda -->
    <section id="beranda"
        class="m-3 mt-28 bg-cover bg-center bg-no-repeat rounded-lg px-4 sm:px-6 md:px-10 py-10"
        style="background-image: url('img/section1.png')">
        <!-- Flash Message -->
@if(session('success'))
<div id="toast" class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    <i class="fas fa-check-circle mr-3"></i> {{ session('success') }}
</div>
@endif
            @if ($errors->getBag('default')->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                class="fixed top-28 right-4 z-50">
                <div class="flex items-center px-6 py-4 rounded-lg shadow-lg bg-red-500 text-white">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <div class="text-md font-semibold">
                        Lengkapi Aduan Anda terlebih dahulu.
                    </div>
                </div>
            </div>
            @endif  
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
                        <div x-data="{ tampil: {{ $errors->any() ? 'true' : 'false' }} }">
                            <button @click="tampil = !tampil" class="cursor-pointer flex items-center gap-6 font-rubik font-medium tracking-wider bg-[#F08619] text-white text-lg px-10 py-4 rounded-full w-60 mx-auto mt-4 hover:bg-[#3B6BA2]" ><i class="fa-solid fa-envelope"></i>Buat Aduan</button>
                            <div x-show="tampil">
                                    <div class="bg-[#000000]/50 absolute top-0 left-0 right-0 bottom-0 lg:h-[1900px] md:h-[2820px] sm:h-[2730px] h-[2830px] lg:p-50 md:px-25 px-10 py-50 z-40">
                                            <div class="bg-white w-auto h-auto items-center justify-center p-4 rounded-lg">
                                                <h1 class="relative bg-[#F08619] text-white py-2 text-center font-roboto rounded-lg font-semibold tracking-wider">
                                                <i @click="tampil = false" class="fa-solid fa-angle-left absolute left-4 top-1/2 -translate-y-1/2 cursor-pointer"></i>
                                                BUAT ADUAN
                                                </h1>  
                                                <div class="bg-[#E0DEDE] mt-2 w-auto h-auto">
                                                <!-- Wrapper Alpine -->
                                                <div x-data="{ tab: 'pelapor' }">

                                                <!-- MENU PILIHAN -->
                                                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5">
                                                    <div
                                                        @click="tab = 'pelapor'"
                                                        :class="tab === 'pelapor' ? 'bg-[#0970A5] text-white' : 'text-[#F08619] hover:bg-[#0970A5] hover:text-white'"
                                                        class="col-span-1 p-3 text-center font-roboto font-semibold tracking-wider cursor-pointer transition">
                                                        PELAPOR
                                                    </div>
                                                    <div
                                                        @click="tab = 'korban'"
                                                        :class="tab === 'korban' ? 'bg-[#0970A5] text-white' : 'text-[#F08619] hover:bg-[#0970A5] hover:text-white'"
                                                        class="col-span-1 p-3 text-center font-roboto font-semibold tracking-wider cursor-pointer transition">
                                                        KORBAN
                                                    </div>
                                                    <div
                                                        @click="tab = 'terlapor'"
                                                        :class="tab === 'terlapor' ? 'bg-[#0970A5] text-white' : 'text-[#F08619] hover:bg-[#0970A5] hover:text-white'"
                                                        class="col-span-1 p-3 text-center font-roboto font-semibold tracking-wider cursor-pointer transition">
                                                        TERLAPOR
                                                    </div>
                                                    <div
                                                        @click="tab = 'peristiwa'"
                                                        :class="tab === 'peristiwa' ? 'bg-[#0970A5] text-white' : 'text-[#F08619] hover:bg-[#0970A5] hover:text-white'"
                                                        class="col-span-1 p-3 text-center font-roboto font-semibold tracking-wider cursor-pointer transition">
                                                        PERISTIWA
                                                    </div>
                                                    <div
                                                        @click="tab = 'tambahan'"
                                                        :class="tab === 'tambahan' ? 'bg-[#0970A5] text-white' : 'text-[#F08619] hover:bg-[#0970A5] hover:text-white'"
                                                        class="col-span-1 p-3 text-center font-roboto font-semibold tracking-wider cursor-pointer transition">
                                                        TAMBAHAN
                                                    </div>
                                                </div>

                                                <!-- FORM -->
                                                <form class="p-6 space-y-6" action="{{ route('aduan.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                    <!-- FORM PELAPOR -->
                                                    <div x-show="tab === 'pelapor'" x-transition>
                                                        <p class="font-bold tracking-wider text-[#F08619]">IDENTITAS PELAPOR</p>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Nama Pelapor <span class="text-[#F08619]">*</span></label>
                                                            <input type="text" name="nama_pelapor" id="nama_pelapor" value="{{ old('nama_pelapor', Auth::user()->fullname) }}" 
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('nama_pelapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619] ">
                                                            @error('nama_pelapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Alamat <span class="text-[#F08619]">*</span></label>
                                                            <input type="text" name="alamat_pelapor" id="alamat_pelapor" value="{{ old('alamat_pelapor') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('alamat_pelapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('alamat_pelapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">File Pernyataan <span class="text-[#F08619]">*</span></label>
                                                            <input type="file" name="pernyataan_pelapor" id="pernyataan_pelapor" accept=".pdf,.doc,.docx"   
                                                            class="lg:w-[500px] rounded-lg bg-white text-base border @error('pernyataan_pelapor') border-red-500 @else border-gray-300 @enderror
                                                                    file:mr-4 file:py-2 file:px-4
                                                                    file:rounded-md file:border-0
                                                                    file:text-sm file:font-semibold
                                                                    file:bg-[#F08619] file:text-white
                                                                    hover:file:bg-[#d97706]
                                                                    focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('pernyataan_pelapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                            <a href="/download-pernyataan" download class="italic underline text-[#008CFF] hover:text-[#005596] pl-20">Klik Unduh File Pernyataan</a>
                                                            
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Email <span class="text-[#F08619]">*</span></label>
                                                            <input type="email" name="email_pelapor" id="email_pelapor" value="{{ old('email_pelapor') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('email_pelapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('email_pelapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">No Hp <span class="text-[#F08619]">*</span></label>
                                                            <input type="tel" name="phone_pelapor" id="phone_pelapor" value="{{ old('phone_pelapor') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('phone_pelapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('phone_pelapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
<div x-data="{ hubungi: '{{ old('hubungi') }}' }">

    <div class="flex flex-col gap-2">
        <label class="font-semibold">
            Bagaimana Anda lebih nyaman dihubungi?
            <span class="text-[#F08619]">*</span>
        </label>

        <div class="space-y-3">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="radio"
                    name="hubungi"
                    value="Email"
                    x-model="hubungi"
                    class="w-5 h-5 text-[#F08619] bg-white"
                    {{ old('hubungi') == 'Email' ? 'checked' : '' }}>
                <span class="font-semibold">Email</span>
            </label>

            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="radio"
                    name="hubungi"
                    value="Whatsapp"
                    x-model="hubungi"
                    class="w-5 h-5 text-[#F08619] bg-white"
                    {{ old('hubungi') == 'Whatsapp' ? 'checked' : '' }}>
                <span class="font-semibold">Whatsapp</span>
            </label>

            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="radio"
                    name="hubungi"
                    value="Facebook"
                    x-model="hubungi"
                    class="w-5 h-5 text-[#F08619] bg-white"
                    {{ old('hubungi') == 'Facebook' ? 'checked' : '' }}>
                <span class="font-semibold">Facebook</span>
            </label>

            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="radio"
                    name="hubungi"
                    value="Instagram"
                    x-model="hubungi"
                    class="w-5 h-5 text-[#F08619] bg-white"
                    {{ old('hubungi') == 'Instagram' ? 'checked' : '' }}>
                <span class="font-semibold">Instagram</span>
            </label>
        </div>

        @error('hubungi')
            <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div x-show="['Facebook', 'Instagram'].includes(hubungi)" x-transition>
        <input type="text"
            name="hubungi_akun"
            value="{{ old('hubungi_akun') }}"
            placeholder="Masukkan akun Sosial Media"
            class="mt-2 rounded-lg bg-white px-5 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#F08619] w-full lg:w-[500px]">

        @error('hubungi_akun')
            <div class="text-red-500 font-semibold mt-1">{{ $message }}</div>
        @enderror
    </div>

</div>
                                                        
                                                        <div class="flex flex-row justify-between items-center w-full mt-4">
                                                            <!-- Tombol Berikutnya -->
                                                            <div @click="tab = 'korban'" class="flex items-center space-x-2 text-[#3B6BA2] cursor-pointer hover:text-[#d97706] font-semibold">
                                                                <span>Berikutnya</span>
                                                                <i class="fa-solid fa-arrow-right"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- FORM KORBAN -->
                                                    <div x-show="tab === 'korban'" x-transition>
                                                        <p class="font-bold tracking-wider text-[#F08619]">IDENTITAS KORBAN</p>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Nama Korban <span class="text-[#F08619]">*</span></label>
                                                            <input type="text" name="nama_korban" id="nama_korban" value="{{ old('nama_korban') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('nama_korban') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('nama_korban')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Jenis Kelamin <span class="text-[#F08619]">*</span></label>
                                                            <select name="jenis_kelamin_korban" id="jenis_kelamin_korban" 
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('jenis_kelamin_korban') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                                                <option value="Laki-laki" {{ old('jenis_kelamin_korban') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                                <option value="Perempuan" {{ old('jenis_kelamin_korban') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                            </select>
                                                            @error('jenis_kelamin_korban')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Alamat </label>
                                                            <input type="text" name="alamat_korban" id="alamat_korban" value="{{ old('alamat_korban') }}" 
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('alamat_korban') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('alamat_korban')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">No HP </label>
                                                            <input type="text" name="phone_korban" id="phone_korban" value="{{ old('phone_korban') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('phone_korban') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('phone_korban')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Status Di Kampus <span class="text-[#F08619]">*</span></label>
                                                            <select type="text" name="status_korban" id="status_korban" 
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('status_korban') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                                <option value="" disabled selected>Pilih Status Di Kampus</option>
                                                                <option value="Pimpinan" {{ old('status_korban') == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                                                <option value="Dosen" {{ old('status_korban') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                                                                <option value="Tenaga Pendidik" {{ old('status_korban') == 'Tenaga Pendidik' ? 'selected' : '' }}>Tenaga Pendidik</option>
                                                                <option value="Satpam" {{ old('status_korban') == 'Satpam' ? 'selected' : '' }}>Satpam</option>
                                                                <option value="OB" {{ old('status_korban') == 'OB' ? 'selected' : '' }}>OB</option>
                                                                <option value="Mahasiswa" {{ old('status_korban') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                                            </select>
                                                            @error('status_korban')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-row justify-between items-center w-full mt-4">
                                                            <!-- Tombol Sebelumnya -->
                                                            <div @click="tab = 'pelapor'" class="flex items-center space-x-2 text-[#3B6BA2] cursor-pointer hover:text-[#d97706] font-semibold">
                                                                <i class="fa-solid fa-arrow-left"></i>
                                                                <span>Sebelumnya</span>
                                                            </div>

                                                            <!-- Tombol Berikutnya -->
                                                            <div @click="tab = 'terlapor'" class="flex items-center space-x-2 text-[#3B6BA2] cursor-pointer hover:text-[#d97706] font-semibold">
                                                                <span>Berikutnya</span>
                                                                <i class="fa-solid fa-arrow-right"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- FORM TERLAPOR -->
                                                    <div x-show="tab === 'terlapor'" x-transition>
                                                        <p class="font-bold tracking-wider text-[#F08619]">IDENTITAS TERLAPOR</p>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Nama Terlapor <span class="text-[#F08619]">*</span></label>
                                                            <input type="text" name="nama_terlapor" id="nama_terlapor" value="{{ old('nama_terlapor') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('nama_terlapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('nama_terlapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Jenis Kelamin <span class="text-[#F08619]">*</span></label>
                                                            <select name="jenis_kelamin_terlapor" id="jenis_kelamin_terlapor" 
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('jenis_kelamin_terlapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                                                <option value="Laki-laki" {{ old('jenis_kelamin_terlapor') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                                <option value="Perempuan" {{ old('jenis_kelamin_terlapor') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                            </select>
                                                            @error('jenis_kelamin_terlapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Alamat </label>
                                                            <input type="text" name="alamat_terlapor" id="alamat_terlapor" value="{{ old('alamat_terlapor') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('alamat_terlapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('alamat_terlapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">No HP </label>
                                                            <input type="text" name="phone_terlapor" id="phone_terlapor" value="{{ old('phone_terlapor') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('phone_terlapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('phone_terlapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>  
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Status Di Kampus <span class="text-[#F08619]">*</span></label>
                                                            <select type="text" name="status_terlapor" id="status_terlapor" 
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('status_terlapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                                <option value="" disabled selected>Pilih Status Di Kampus</option>
                                                                <option value="Pimpinan" {{ old('status_terlapor') == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                                                <option value="Dosen" {{ old('status_terlapor') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                                                                <option value="Tenaga Pendidik" {{ old('status_terlapor') == 'Tenaga Pendidik' ? 'selected' : '' }}>Tenaga Pendidik</option>
                                                                <option value="Satpam" {{ old('status_terlapor') == 'Satpam' ? 'selected' : '' }}>Satpam</option>
                                                                <option value="OB">OB</option>
                                                                <option value="Mahasiswa">Mahasiswa</option>
                                                            </select>
                                                            @error('status_terlapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Karakteristik Fisik<span class="text-[#F08619]">*</span></label>
                                                            <textarea name="karakteristik_terlapor" id="karakteristik_terlapor"
                                                            class="lg:w-[500px] h-[100px] rounded-lg bg-white px-5 py-2 text-base border @error('karakteristik_terlapor') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619] resize-none">{{ old('karakteristik_terlapor') }}</textarea>
                                                            @error('karakteristik_terlapor')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Apakah terlapor pernah terlibat dalam kejadian kekerasan lainnya?<span class="text-[#F08619]">*</span></label>
                                                            <div class="flex flex-row gap-2 text-center">
                                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                                    <input type="radio" name="terlapor" value="Iya" {{ old('terlapor') == 'Iya' ? 'checked' : '' }}
                                                                    class="w-5 h-5 text-[#F08619] bg-white">
                                                                    <span class="font-semibold">Ya</span>
                                                                </label>

                                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                                    <input type="radio" name="terlapor" value="Tidak" {{ old('terlapor') == 'Tidak' ? 'checked' : '' }}
                                                                    class="w-5 h-5 text-[#F08619] bg-white">
                                                                    <span class="font-semibold">Tidak</span>
                                                                </label>
                                                            </div>
                                                            @error('terlapor')
                                                            <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div x-data="{ warning: '{{ old('warning') }}' }" class="flex flex-col gap-2">
                                                        <label class="font-semibold" for="">
                                                            Apakah terlapor sudah diberi peringatan atau tindakan sebelumnya? Jika ada, jelaskan
                                                            <span class="text-[#F08619]">*</span>
                                                        </label>

                                                        <div class="flex flex-row gap-4 text-center">
                                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                                <input type="radio" name="warning" value="Iya" x-model="warning" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white">
                                                                <span class="font-semibold">Ya</span>
                                                            </label>

                                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                                <input type="radio" name="warning" value="Tidak" x-model="warning"
                                                                    class="w-5 h-5 text-[#F08619] bg-white">
                                                                <span class="font-semibold">Tidak</span>
                                                            </label>
                                                        </div>
                                                        @error('warning')
                                                            <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                        @enderror

                                                        <!-- Input tambahan muncul jika memilih "Ya" -->
                                                        <div x-show="warning === 'Iya'" x-transition>
                                                            <input type="text" name="warning_detail" placeholder="Jelaskan tindakan atau peringatan sebelumnya" value="{{ old('warning_detail') }}"
                                                                class="mt-2 rounded-lg bg-white px-5 py-2 text-base border @error('warning_detail') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619] w-full lg:w-[500px]">
                                                            @error('warning_detail')
                                                                <div class="text-red-500 font-semibold mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        </div>   
                                                        <div class="flex flex-row justify-between items-center w-full mt-4">
                                                        <!-- Tombol Sebelumnya -->
                                                        <div @click="tab = 'terlapor'" class="flex items-center space-x-2 text-[#3B6BA2] cursor-pointer hover:text-[#d97706] font-semibold">
                                                            <i class="fa-solid fa-arrow-left"></i>
                                                            <span>Sebelumnya</span>
                                                        </div>

                                                        <!-- Tombol Berikutnya -->
                                                        <div @click="tab = 'peristiwa'" class="flex items-center space-x-2 text-[#3B6BA2] cursor-pointer hover:text-[#d97706] font-semibold">
                                                            <span>Berikutnya</span>
                                                            <i class="fa-solid fa-arrow-right"></i>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- FORM PERISTIWA -->
                                                    <div x-show="tab === 'peristiwa'" x-transition>
                                                        <p class="font-bold tracking-wider text-[#F08619]">PERISTIWA</p>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Tanggal Peristiwa <span class="text-[#F08619]">*</span></label>
                                                            <input type="date" name="tanggal_peristiwa" id="tanggal_peristiwa" value="{{ old('tanggal_peristiwa') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('tanggal_peristiwa') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('tanggal_peristiwa')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Kategori <span class="text-[#F08619]">*</span></label>
                                                            <select type="text" name="category" id="category" 
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('category') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                                <option value="" disabled selected>Pilih Kategori</option>
                                                                <option value="Fisik" {{ old('category') == 'Fisik' ? 'selected' : '' }}>Fisik</option>
                                                                <option value="Verbal" {{ old('category') == 'Verbal' ? 'selected' : '' }}>Verbal</option>
                                                                <option value="Seksual" {{ old('category') == 'Seksual' ? 'selected' : '' }}>Seksual</option>
                                                                <option value="Psikologis" {{ old('category') == 'Psikologis' ? 'selected' : '' }}>Psikologis</option>
                                                            </select>
                                                            @error('category')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Kronologi Peristiwa <span class="text-[#F08619]">*</span></label>
                                                            <textarea name="chronology" id="chronology"
                                                            class="lg:w-[500px] h-[100px] rounded-lg bg-white px-5 py-2 text-base border @error('chronology') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619] resize-none">{{ old('chronology') }}</textarea>
                                                            @error('chronology')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">File Bukti </label>
                                                            <input 
                                                                type="file" 
                                                                name="bukti_pelaporan" 
                                                                id="bukti_pelaporan"
                                                                accept="image/*,video/*,audio/*,.pdf"
                                                                class="lg:w-[500px] rounded-lg bg-white text-base border 
                                                                    @error('bukti_pelaporan') border-red-500 
                                                                    @else border-gray-300 
                                                                    @enderror
                                                                    file:mr-4 file:py-2 file:px-4
                                                                    file:rounded-md file:border-0
                                                                    file:text-sm file:font-semibold
                                                                    file:bg-[#F08619] file:text-white
                                                                    hover:file:bg-[#d97706]
                                                                    focus:outline-none focus:ring-2 focus:ring-[#F08619]"
>

                                                        </div> 
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Lokasi <span class="text-[#F08619]">*</span></label>
                                                            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                                                            class="lg:w-[500px] rounded-lg bg-white px-5 py-2 text-base border @error('lokasi') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                                                            @error('lokasi')
                                                                <div class="text-red-500 font-semibold -mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="flex flex-row justify-between items-center w-full mt-4">
                                                        <!-- Tombol Sebelumnya -->
                                                        <div @click="tab = 'terlapor'" class="flex items-center space-x-2 text-[#3B6BA2] cursor-pointer hover:text-[#d97706] font-semibold">
                                                            <i class="fa-solid fa-arrow-left"></i>
                                                            <span>Sebelumnya</span>
                                                        </div>

                                                        <!-- Tombol Berikutnya -->
                                                        <div @click="tab = 'tambahan'" class="flex items-center space-x-2 text-[#3B6BA2] cursor-pointer hover:text-[#d97706] font-semibold">
                                                            <span>Berikutnya</span>
                                                            <i class="fa-solid fa-arrow-right"></i>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- FORM TAMBAHAN -->
                                                    <div x-show="tab === 'tambahan'" x-transition>
                                                        <p class="font-bold tracking-wider text-[#F08619]">TAMBAHAN INFORMASI</p>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Apakah pelaku masih berpotensi melakukan kekerasan lebih lanjut terhadap korban? <span class="text-[#F08619]">*</span></label>
                                                            <div class="space-y-0.5">
                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="berpotensi" value="10" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white "
                                                                    {{ old('berpotensi') == '10' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Sangat berpotensi</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="berpotensi" value="7"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('berpotensi') == '7' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Berpotensi</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="berpotensi" value="4"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('berpotensi') == '4' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak berpotensi</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="berpotensi" value="1"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('berpotensi') == '1' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak yakin</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Seberapa parah dampak fisik yang dialami korban? <span class="text-[#F08619]">*</span></label>
                                                            <div class="space-y-0.5">
                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="dampak_fisik" value="10" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('dampak_fisik') == '10' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Luka berat (membutuhkan perawatan medis intensif)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="dampak_fisik" value="7"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('dampak_fisik') == '7' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Luka ringan (tidak memerlukan perawatan intensif)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="dampak_fisik" value="4"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('dampak_fisik') == '4' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak ada luka fisik</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="dampak_fisik" value="1"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('dampak_fisik') == '1' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak yakin</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Seberapa parah dampak psikologis yang dialami korban? <span class="text-[#F08619]">*</span></label>
                                                            <div class="space-y-0.5">
                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="dampak_psikologis" value="10" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white "
                                                                    {{ old('dampak_psikologis') == '10' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Trauma berat (gangguan fungsi harian)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="dampak_psikologis" value="7"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('dampak_psikologis') == '7' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Gangguan psikologis sedang (stres/cemas)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="dampak_psikologis" value="4"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('dampak_psikologis') == '4' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak ada dampak psikologis</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="dampak_psikologis" value="1"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('dampak_psikologis') == '1' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak yakin</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Apakah kasus ini merupakan kejadian berulang? <span class="text-[#F08619]">*</span></label>
                                                            <div class="space-y-0.5">
                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="berulang" value="10" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white "
                                                                    {{ old('berulang') == '10' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Sudah sering terjadi (lebih dari 5 kali)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="berulang" value="7"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('berulang') == '7' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Beberapa kali terjadi (2-5 kali)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="berulang" value="4"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('berulang') == '4' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Baru pertama kali terjadi (1-2 kali)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="berulang" value="1"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('berulang') == '1' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak yakin</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Apakah kejadian ini mempengaruhi hubungan sosial korban? <span class="text-[#F08619]">*</span></label>
                                                            <div class="space-y-0.5">
                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="hubungan_sosial" value="10" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white "
                                                                    {{ old('hubungan_sosial') == '10' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Gangguan hubungan yang parah (isolasi sosial total)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="hubungan_sosial" value="7"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('hubungan_sosial') == '7' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Gangguan hubungan yang sedang (isolasi sosial parah)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="hubungan_sosial" value="4"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('hubungan_sosial') == '4' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak ada gangguan hubungan sosial</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="hubungan_sosial" value="1"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('hubungan_sosial') == '1' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak yakin</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Apakah kejadian ini mempengaruhi kinerja akademik atau pekerjaan korban?  <span class="text-[#F08619]">*</span></label>
                                                            <div class="space-y-0.5">
                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="kinerja" value="10" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white "
                                                                    {{ old('kinerja') == '10' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Sangat mempengaruhi (absen panjang, penurunan signifikan)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="kinerja" value="7"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                     {{ old('kinerja') == '7' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Sedikit mempengaruhi (gangguan sementara)</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="kinerja" value="4"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                     {{ old('kinerja') == '4' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak ada pengaruh terhadap kinerja</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="kinerja" value="1"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                     {{ old('kinerja') == '1' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak yakin</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Bagaimana Anda menilai tingkat keseriusan kasus ini secara keseluruhan? * <span class="text-[#F08619]">*</span></label>
                                                            <div class="space-y-0.5">
                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="keseriusan" value="10" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white "
                                                                     {{ old('keseriusan') == '10' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Sangat serius</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="keseriusan" value="7"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                     {{ old('keseriusan') == '7' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Serius</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="keseriusan" value="4"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('keseriusan') == '4' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Ringan</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="keseriusan" value="1"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('keseriusan') == '1' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak yakin</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Apakah kejadian ini terjadi di lingkungan kampus atau tempat kerja korban? <span class="text-[#F08619]">*</span></label>
                                                            <div class="space-y-0.5">
                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="lingkungan" value="10" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white "
                                                                    {{ old('lingkungan') == '10' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Ya, terjadi di lingkungan langsung korban</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="lingkungan" value="7"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('lingkungan') == '7' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Ya, terjadi di lingkungan lain tetapi berhubungan dengan korban</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="lingkungan" value="4"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('lingkungan') == '4' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak terkait dengan lingkungan korban</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="lingkungan" value="1"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('lingkungan') == '1' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak yakin</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label class="font-semibold" for="">Apakah korban bersedia bekerja sama dengan pihak berwenang untuk menyelesaikan kasus ini? <span class="text-[#F08619]">*</span></label>
                                                            <div class="space-y-0.5">
                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="bersedia" value="Siap klarifikasi" 
                                                                    class="w-5 h-5 text-[#F08619] bg-white "
                                                                    {{ old('bersedia') == 'Siap klarifikasi' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Ya, sangat bersedia</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="bersedia" value="Perlu pendekatan bertahap"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('bersedia') == 'Perlu pendekatan bertahap' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Ya, tetapi ragu-ragu</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="bersedia" value="Fokus perlindungan korban"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('bersedia') == 'Fokus perlindungan korban' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak bersedia</span>
                                                                </label>

                                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                                    <input type="radio" name="bersedia" value="Fokus perlindungan korban"
                                                                    class="w-5 h-5 text-[#F08619] bg-white"
                                                                    {{ old('bersedia') == 'Fokus perlindungan korban' ? 'checked' : '' }}>
                                                                    <span class="font-semibold">Tidak yakin</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-row justify-between items-center w-full mt-4">
                                                        <!-- Tombol Sebelumnya -->
                                                        <div @click="tab = 'peristiwa'" class="flex items-center space-x-2 text-[#3B6BA2] hover:text-[#d97706] font-semibold">
                                                            <i class="fa-solid fa-arrow-left"></i>
                                                            <span>Sebelumnya</span>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div x-show="tab === 'tambahan'" class="flex flex-col sm:flex-row justify-center mt-4 gap-2">
                                                        <button type="submit" class="bg-[#F08619] text-white px-6 py-2 rounded-lg hover:bg-[#3B6BA2]">
                                                            <i class="fa-solid fa-paper-plane mr-2"></i>
                                                            Kirim Aduan
                                                        </button>
                                                        <button type="reset" class="bg-[#F08619] text-white px-6 py-2 rounded-lg hover:bg-[#3B6BA2]">
                                                            <i class="fa-solid fa-rotate mr-2"></i>
                                                            Hapus Semua
                                                        </button>
                                                        <a @click="tampil = false" href="#" class="bg-[#F08619] text-white px-6 py-2 text-center rounded-lg hover:bg-[#3B6BA2]">
                                                            <i class="fa-solid fa-xmark mr-2"></i>
                                                            Tutup
                                                        </a>
                                                    </div>
                                                </form>
                                                </div>
                                                </div>
                                            </div>
                                    </div>
                            </div>
                        </div>
                        <div x-data="{ hasil: false, selectedAduan: null, searchTerm: '' }" >
                            <button @click="hasil = !hasil" class="cursor-pointer flex items-center gap-6 font-rubik font-medium tracking-wider bg-transparent outline-solid text-gray-50 text-lg px-10 py-4 rounded-full w-60 mx-auto mt-4 hover:bg-[#3B6BA2]" ><i class="fa-regular fa-bell"></i> Hasil Aduan</button>
                            <div x-show="hasil">
                            <div class="bg-[#000000]/50 absolute top-0 left-0 right-0 bottom-0 lg:h-[1900px] md:h-[2820px] sm:h-[2730px] h-[2830px] lg:p-50 md:px-25 px-10 py-50 z-40">
                                <div class="bg-white w-auto h-auto items-center justify-center p-4 rounded-lg">
                                    <h1 class="relative bg-[#F08619] text-white py-2 text-center font-roboto rounded-lg font-semibold tracking-wider">
                                        <i @click="hasil = false" class="fa-solid fa-angle-left absolute left-4 top-1/2 -translate-y-1/2 cursor-pointer"></i>
                                        Hasil Aduan
                                    </h1>  
                                    <div class="bg-[#E0DEDE] mt-2 p-6 w-full h-auto flex flex-col lg:flex-row gap-5">
                                        <div class="p-2 lg:w-3/7">
                                            <!-- Pencarian -->
                                            <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-2 bg-white w-full md:w-auto">
                                                <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                                                <input
                                                    type="text"
                                                    placeholder="Cari Aduan..."
                                                    class="outline-none w-full"
                                                    id="searchInput"
                                                    x-model="searchTerm">
                                            </div>
                                            <div class="lg:max-h-[450px] overflow-y-auto mt-4 p-2">
                                                @forelse ($aduans as $aduan)
                                                <div x-show="!searchTerm || '{{ $aduan->kode_aduan }}'.toLowerCase().includes(searchTerm.toLowerCase())" 
                                                     class="flex gap-4 items-center bg-white lg:p-4 md:p-2 p-2 rounded-lg shadow-sm mb-2 cursor-pointer hover:bg-gray-100" 
                                                     @click="selectedAduan = selectedAduan === {{ $aduan->id }} ? null : {{ $aduan->id }}"
                                                     :class="selectedAduan === {{ $aduan->id }} ? 'ring-2 ring-[#F08619]' : ''">
                                                    <div class="justify-center">
                                                        <i class="{{ $aduan->icon }} text-[#F08619] text-5xl"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold">{{$aduan->kode_aduan}}</h4>
                                                        <p class="text-gray-500 text-sm">{{ $aduan->created_at->translatedFormat('d F Y') }}</p>
                                                    </div>
                                                </div>
                                                @empty
                                                <p class="text-gray-500 italic">Belum ada aduan yang Anda kirimkan.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="mt-2 lg:w-3/5">
                                            <h3 class="bg-[#F08619] text-white px-8 py-2 rounded-full font-semibold w-[200px] text-center">Status Aduan</h3>
                                            <div class="relative border-l-4 border-[#F08619] pl-2 space-y-6 mt-6">
                                                <div x-show="!selectedAduan" class="text-gray-500 italic">
                                                    
                                                </div>
                                                
                                                @forelse ($aduans as $aduan)
                                                <div x-show="selectedAduan === {{ $aduan->id }}" x-transition>
                                                    @php
                                                        $steps = [];
                                                        $status = null;
                                                        
                                                        // Handle different types of status relationships
                                                        if ($aduan->statuses) {
                                                            if (is_iterable($aduan->statuses)) {
                                                                $status = $aduan->statuses->first();
                                                            } else {
                                                                $status = $aduan->statuses;
                                                            }
                                                        }
                                                        
                                                        if ($status) {
                                                            $steps = [
                                                                ['label' => $status->label4 ?? null, 'value' => $status->status4 ?? null],
                                                                ['label' => $status->label3 ?? null, 'value' => $status->status3 ?? null],
                                                                ['label' => $status->label2 ?? null, 'value' => $status->status2 ?? null],
                                                                ['label' => $status->label1 ?? null, 'value' => $status->status1 ?? null],
                                                            ];
                                                            
                                                            // Ambil hanya yang ada isinya
                                                            $steps = array_filter($steps, fn($s) => !empty($s['value']));
                                                        }
                                                    @endphp

                                                    @if(empty($steps))
                                                        <p class="text-gray-500 italic">Belum ada status untuk aduan ini.</p>
                                                    @else
                                                        @foreach ($steps as $step)
                                                            <div class="relative flex gap-2 items-center">
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

                                        
                                                @foreach ($aduans as $aduan)
                                                    <div id="penolakan-{{ $aduan->id }}" 
                                                        class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 target:flex">
                                                        <div class="bg-white p-6 rounded-lg w-[90%] md:w-[600px] relative">
                                                            <h2 class="text-lg font-bold text-[#F08619] mb-2">Detail Penolakan</h2>
                                                            <div class="text-gray-700 mb-4 text-justify">
                                                                <p>Kepada Yth.<br>{{ $aduan->nama_pelapor ?? 'Nama tidak tersedia' }}</p>
                                                                <p>Berdasarkan hasil pemeriksaan awal terhadap laporan yang Anda kirimkan,  
                                                                kami informasikan bahwa laporan tersebut <strong>belum dapat diproses lebih lanjut</strong> karena data yang diberikan tidak sesuai dengan ketentuan pelaporan.  
                                                                </p>
                                                                <p>Alasan penolakan: <br>
                                                                {!! $aduan->statuses->first()->penolakan ?? 'Alasan penolakan tidak tersedia' !!}</p>
                                                                <p>Anda dapat mengirimkan kembali laporan baru dengan memperbaiki data tersebut.  
                                                                Terima kasih atas perhatian dan kerja samanya.  
                                                                </p> <br>
                                                                <p>Hormat kami, <br>
                                                                <strong>Admin PPKPT ITH</strong></p>
                                                            </div>

                                                            <a href="#" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                                                                <i class="fa-solid fa-xmark text-xl"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach

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
