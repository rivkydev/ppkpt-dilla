@props(['beritas'])

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- ===================== --}}
    {{-- KIRI: BERITA UTAMA --}}
    {{-- ===================== --}}
    <div>
        @if($beritas->isNotEmpty())
            <div class="relative">
                <img class="w-full h-[400px] object-cover rounded-lg"
                     src="{{ asset('storage/' . $beritas->first()->gambar) }}"
                     alt="{{ $beritas->first()->judul }}">

                <div class="absolute bottom-0 left-0 right-0 bg-[#232323]/80 rounded-lg">
                    <div class="p-6">
                        <a href="{{ route('berita', $beritas->first()->id) }}"
                           class="text-white font-bold text-xl">
                            {{ $beritas->first()->judul }}
                        </a>

                        <p class="text-[#00F0FF] text-base mt-2">
                            {{ Str::limit(strip_tags($beritas->first()->isi), 200) }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ===================== --}}
    {{-- KANAN: LIST BERITA --}}
    {{-- ===================== --}}
    <div class="h-[400px] overflow-y-auto space-y-4 pr-2">

        @foreach($beritas->skip(1) as $berita)
            <div class="flex flex-row">

                <img class="w-1/3 h-auto object-cover rounded-lg"
                     src="{{ asset('storage/' . $berita->gambar) }}">

                <div class="bg-[#232323] rounded-lg p-4 w-full">    
                    <a href="{{ route('berita', $berita->id) }}"
                       class="text-white font-bold text-lg">
                        {{ $berita->judul }}
                    </a>

                    <p class="text-[#00F0FF] text-sm mt-1">
                        {{ Str::limit(strip_tags($berita->isi), 200) }}
                    </p>
                </div>

            </div>
        @endforeach

    </div>

</div>