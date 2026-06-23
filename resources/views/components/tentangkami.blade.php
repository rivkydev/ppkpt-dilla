<div class="mx-auto max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Kontak & Form -->
            <div class="text-gray-50">
                <h1 class="font-bold tracking-widest text-4xl sm:text-5xl text-left">KONTAK</h1>
                <h4 class="font-semibold tracking-widest text-lg sm:text-xl pt-8"><i class="fa-solid fa-phone"></i> 0895-7923-6768</h4>
                <h4 class="font-semibold tracking-widest text-lg sm:text-xl pt-2"><i class="fa-solid fa-envelope"></i> satgas.ppks@ith.ac.id</h4>
                <h4 class="font-semibold tracking-widest text-lg sm:text-xl pt-2"><i class="fa-brands fa-instagram"></i> ppkpt.ith</h4>

                <form class="space-y-3 mt-6" action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    @error('nama')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <input type="text" name="nama" placeholder="Nama" value="{{ old('nama', auth()->check() ? auth()->user()->fullname : '') }}" 
                        class="w-full rounded-xl bg-white px-5 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                    
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}"
                        class="w-full rounded-xl bg-white px-5 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F08619]">
                    
                    @error('pesan')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <textarea name="pesan" placeholder="Pesan" value="{{ old('pesan') }}"
                        class="w-full h-40 rounded-xl bg-white px-5 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F08619] resize-none"></textarea>
                    <button type="submit"
                        class="w-full rounded-xl bg-[#F08619] px-5 py-3 text-sm font-bold text-white shadow hover:bg-[#3B6BA2] tracking-wider font-rubik">Kirim</button>
                </form>
            </div>

            <!-- Alamat & Peta -->
            <div class="text-gray-50 text-center lg:text-left flex flex-col justify-center">
                <h4 class="font-medium tracking-wider text-base sm:text-lg font-roboto mb-4 text-center justify-center">
                    Kampus 1 Jalan Balai Kota No.1<br>
                    Kota Parepare, Sulawesi Selatan, Indonesia
                </h4>
                <div class="w-full">
                    <iframe class="w-full h-64 sm:h-80 rounded-xl"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3979.9591203870123!2d119.63075877367123!3d-4.028759844771094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d95bbb762bcd13d%3A0x95845218a1ae2419!2sInstitut%20Teknologi%20Bacharuddin%20Jusuf%20Habibie%20(ITH)%20-%20Kampus%201!5e0!3m2!1sid!2sid!4v1717369585160!5m2!1sid!2sid"
                        style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>