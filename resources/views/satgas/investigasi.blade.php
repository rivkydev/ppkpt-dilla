<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PPKPT ITH</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link
      rel="stylesheet"  
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
</head>
<body>
    <x-nav-baar></x-nav-baar>
    <div class="flex mt-31">
        <div class="h-[520px] w-[300px] bg-[#0970A5] px-4 py-15 shadow-lg rounded-lg lg:block hidden">
            <x-sidebarr :active="'laporan-ditangani'"></x-sidebarr>
            <div class="bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 w-[100%] lg:h-[520px] h-screen p-2 overflow-y-auto">
                <div class="px-6 py-4 relative w-full flex items-center">
                  <!-- Tombol Kembali -->
                  <a href="{{ route('satgas.laporanditangani') }}"
                  class="absolute left-8 flex items-center justify-center
                          w-9 h-9 rounded-full bg-[#0970A5]
                          text-white hover:bg-[#085d88]
                          transition shadow">
                      <i class="fa-solid fa-angle-left"></i>
                  </a>

                  <!-- Judul -->
                  <h5 class="font-bold tracking-widest
                          bg-[#0970A5] text-gray-50 text-center
                          rounded-xl w-full py-3 text-xl shadow-md">
                      INVESTIGASI
                  </h5>
                </div>
                <div class="bg-[#E0DEDE] px-6 rounded-xl">
                    <form action="{{ route('satgas.investigasiStore', $aduan->id) }}" method="post" enctype="multipart/form-data">
                         @csrf
                      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-semibold text-[#0970A5]
                                    border-l-4 border-[#0970A5] pl-3">
                                Informasi Aduan
                            </h2>
                            <a href="{{ route('satgas.detaillaporan', $aduan->kode_aduan) }}"
                            class="inline-block text-white bg-[#0970A5] hover:bg-[#065a84] px-4 py-2 rounded-lg font-medium text-sm transition">Lihat Detail</a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                          <div class="flex flex-col">
                              <label class="text-gray-600 mb-1 font-medium">Kode Aduan</label>
                              <input type="text" name="kode_aduan" value="{{ $aduan->kode_aduan }}" 
                                    readonly
                                    class="border border-gray-300 bg-gray-100 text-gray-700
                                            rounded-lg px-3 py-2 cursor-not-allowed
                                            focus:outline-none">

                          </div>
                          <div class="flex flex-col">
                              <label class="text-gray-600 mb-1 font-medium">Tanggal Peristiwa</label>
                              <input type="text" name="tanggal" value="{{ \Carbon\Carbon::parse($aduan->tanggal_peristiwa)->translatedFormat('d F Y') }}"
                                    readonly
                                    class="border border-gray-300 bg-gray-100 text-gray-700
                                            rounded-lg px-3 py-2 cursor-not-allowed
                                            focus:outline-none">
                          </div>
                          <div class="flex flex-col">
                              <label class="text-gray-600 mb-1 font-medium">Jenis Kekerasan</label>
                              <input type="text" name="jenis_kekerasan" value="{{ $aduan->category }}" 
                                    class="border border-gray-300 bg-gray-100 text-gray-700
                                            rounded-lg px-3 py-2 cursor-not-allowed
                                            focus:outline-none">
                          </div>
                          <div class="flex flex-col">
                                <label class="text-gray-600 mb-1 font-medium">Lokasi Kejadian</label>
                                <input type="text" name="lokasi_kejadian" value="{{ $aduan->lokasi }}"
                                    readonly
                                    class="border border-gray-300 bg-gray-100 text-gray-700
                                            rounded-lg px-3 py-2 cursor-not-allowed
                                            focus:outline-none">
                          </div>
                          
                        </div>
                    </div>  

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-semibold text-[#0970A5]
                                    border-l-4 border-[#0970A5] pl-3">
                                Pihak Terkait
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-sm">
                          <!-- Card Data Korban -->
                          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-[#0970A5] border-l-4 border-[#0970A5] pl-3 mb-6">
                                Data Korban
                            </h3>

                            <div class="space-y-4">
                                <!-- Nama Korban -->
                                <div class="flex flex-col">
                                    <label for="nama_korban" class="text-gray-600 font-medium mb-1">Nama Korban</label>
                                    <input type="text" id="nama_korban" name="nama_korban" value="{{ $aduan->nama_korban }}"
                                        readonly
                                    class="border border-gray-300 bg-gray-100 text-gray-700
                                            rounded-lg px-3 py-2 cursor-not-allowed
                                            focus:outline-none">
                                </div>

                                <!-- Status -->
                                <div class="flex flex-col">
                                    <label for="status_korban" class="text-gray-600 font-medium mb-1">Status</label>
                                    <input type="text" id="status_korban" name="status_korban" value="{{ $aduan->status_korban }}"
                                        readonly
                                    class="border border-gray-300 bg-gray-100 text-gray-700
                                            rounded-lg px-3 py-2 cursor-not-allowed
                                            focus:outline-none">
                                </div>
                            </div>
                        </div>


                          <!-- Card Data Terlapor -->
                          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                              <h3 class="text-base font-semibold text-[#0970A5] border-l-4 border-[#0970A5] pl-3 mb-4">
                                  Data Terlapor
                              </h3>
                              <div class="space-y-4">

                                <div class="flex flex-col">
                                    <label for="nama_korban" class="text-gray-600 font-medium mb-1">Nama Terlapor</label>
                                    <input type="text" id="nama_terlapor" name="nama_terlapor" value="{{ $aduan->nama_terlapor }}"
                                        readonly
                                    class="border border-gray-300 bg-gray-100 text-gray-700
                                            rounded-lg px-3 py-2 cursor-not-allowed
                                            focus:outline-none">
                                </div>

                                <!-- Status -->
                                <div class="flex flex-col">
                                    <label for="status_terlapor" class="text-gray-600 font-medium mb-1">Status</label>
                                    <input type="text" id="status_terlapor" name="status_terlapor" value="{{ $aduan->status_terlapor }}"
                                        readonly
                                    class="border border-gray-300 bg-gray-100 text-gray-700
                                            rounded-lg px-3 py-2 cursor-not-allowed
                                            focus:outline-none">
                                </div>
                            </div>
                          </div>

                          <!-- Card Data Saksi -->
                          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                                <h3 class="text-base font-semibold text-[#0970A5] border-l-4 border-[#0970A5] pl-3 mb-4">
                                    Data Saksi
                                </h3>

                                <div class="space-y-4">
                                    <!-- Nama Saksi -->
                                    <div class="flex flex-col">
                                        <label for="nama_saksi" class="text-gray-600 font-medium mb-1">
                                            Nama Saksi <span class="text-gray-400 text-sm">(Opsional)</span>
                                        </label>

                                        <input
                                            type="text"
                                            id="nama_saksi"
                                            name="nama_saksi"
                                            value="{{ old('nama_saksi', $investigasi->nama_saksi ?? '') }}"
                                            placeholder="Masukkan nama saksi jika ada"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2
                                                focus:outline-none focus:ring-2 focus:ring-[#0970A5]"
                                        >
                                    </div>

                                    <!-- Keterangan Singkat -->
                                    <div class="flex flex-col">
                                        <label for="keterangan_saksi" class="text-gray-600 font-medium mb-1">
                                            Keterangan Singkat <span class="text-gray-400 text-sm">(Opsional)</span>
                                        </label>

                                        <textarea
                                            name="keterangan_saksi"
                                            id="keterangan_saksi"
                                            rows="3"
                                            placeholder="Tuliskan keterangan singkat saksi (jika ada)"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2
                                                focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none"
                                        >{{ old('keterangan_saksi', $investigasi->keterangan_saksi ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                      </div>

                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                      <h2 class="text-base font-semibold text-[#0970A5] border-l-4 border-[#0970A5] pl-3 mb-4">
                          Proses Investigasi
                      </h2>

                      <!-- Checklist -->
                      @php
                            $prosesTersimpan = old(
                                'proses',
                                isset($investigasi) && $investigasi->proses
                                    ? json_decode($investigasi->proses, true)
                                    : []
                            );
                        @endphp

                        <div class="flex flex-col space-y-2 mb-4">

                            <label class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="proses[]"
                                    value="wawancara_korban"
                                    class="accent-[#0970A5]"
                                    {{ in_array('wawancara_korban', $prosesTersimpan) ? 'checked' : '' }}
                                >
                                Wawancara Korban
                            </label>

                            <label class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="proses[]"
                                    value="wawancara_saksi"
                                    class="accent-[#0970A5]"
                                    {{ in_array('wawancara_saksi', $prosesTersimpan) ? 'checked' : '' }}
                                >
                                Wawancara Saksi
                            </label>

                            <label class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="proses[]"
                                    value="wawancara_terlapor"
                                    class="accent-[#0970A5]"
                                    {{ in_array('wawancara_terlapor', $prosesTersimpan) ? 'checked' : '' }}
                                >
                                Wawancara Terlapor
                            </label>

                            <label class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="proses[]"
                                    value="pemeriksaan_bukti"
                                    class="accent-[#0970A5]"
                                    {{ in_array('pemeriksaan_bukti', $prosesTersimpan) ? 'checked' : '' }}
                                >
                                Pemeriksaan Bukti
                            </label>

                        </div>



                      <!-- Textarea Catatan -->
                      <div class="flex flex-col">
                          <label class="text-gray-600 font-medium mb-1">
                                Catatan Singkat Proses Investigasi
                                <span class="text-gray-400 text-sm">(Opsional)</span>
                            </label>
                          <textarea name="catatan_proses" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none" 
                                    placeholder="Tulis catatan singkat di sini...">{{ old('catatan_proses', $investigasi->catatan_proses ?? '') }}</textarea>
                      </div>
                  </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h2 class="text-base font-semibold text-[#0970A5] border-l-4 border-[#0970A5] pl-3 mb-4">
                            Temuan Investigasi
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                            <!-- Kronologi -->
                            <div class="flex flex-col">
                                <label class="text-gray-600 mb-1 font-medium">Kronologi Kejadian (Versi Tim Investigasi)</label>
                                <textarea name="kronologi" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none">{{ old('kronologi', $investigasi->kronologi ?? '') }}</textarea>
                            </div>

                            <!-- Ringkasan Wawancara Korban -->
                            <div class="flex flex-col">
                                <label class="text-gray-600 mb-1 font-medium">Ringkasan Wawancara Korban <span class="text-gray-400 text-sm">(Opsional)</span></label>
                                <textarea name="wawancara_korban" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none">{{ old('wawancara_korban', $investigasi->wawancara_korban ?? '') }}</textarea>
                            </div>

                            <!-- Ringkasan Wawancara Saksi -->
                            <div class="flex flex-col">
                                <label class="text-gray-600 mb-1 font-medium">Ringkasan Wawancara Saksi <span class="text-gray-400 text-sm">(Opsional)</span></label>
                                <textarea name="wawancara_saksi" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none">{{ old('wawancara_saksi', $investigasi->wawancara_saksi ?? '') }}</textarea>
                            </div>

                            <!-- Ringkasan Wawancara Terlapor -->
                            <div class="flex flex-col">
                                <label class="text-gray-600 mb-1 font-medium">Ringkasan Wawancara Terlapor <span class="text-gray-400 text-sm">(Opsional)</span></label>
                                <textarea name="wawancara_terlapor" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none">{{ old('wawancara_terlapor', $investigasi->wawancara_terlapor ?? '') }}</textarea>
                            </div>

                            <!-- Fakta Terbukti -->
                            <div class="flex flex-col">
                                <label class="text-gray-600 mb-1 font-medium">Fakta Yang Terbukti <span class="text-gray-400 text-sm">(Opsional)</span></label>
                                <textarea name="fakta_terbukti" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none">{{ old('fakta_terbukti', $investigasi->fakta_terbukti ?? '') }}</textarea>
                                <input 
                                    type="file" 
                                    name="file_terbukti"
                                    class="mt-2 w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-[#0970A5] file:text-white
                                          hover:file:bg-[#085d88]
                                          focus:outline-none focus:ring-2 focus:ring-[#0970A5]"
                                />

                            </div>

                            <!-- Fakta Tidak Terbukti -->
                            <div class="flex flex-col">
                                <label class="text-gray-600 mb-1 font-medium">Fakta Yang Tidak Terbukti <span class="text-gray-400 text-sm">(Opsional)</span></label>
                                <textarea name="fakta_tidak_terbukti" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none">{{ old('fakta_tidak_terbukti', $investigasi->fakta_tidak_terbukti ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h2 class="text-base font-semibold text-[#0970A5] border-l-4 border-[#0970A5] pl-3 mb-6">
                            Hasil & Tindak Lanjut
                        </h2>

                        <!-- Checklist Tindak Lanjut -->
                        <div class="mb-6">
                            <span class="block text-gray-600 font-medium mb-3">Tindak Lanjut</span>
                            @php
                                $tindakLanjut = old(
                                    'tindak_lanjut',
                                    isset($investigasi) && $investigasi->tindak_lanjut
                                        ? json_decode($investigasi->tindak_lanjut, true)
                                        : []
                                );
                            @endphp

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">

                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="tindak_lanjut[]"
                                        value="Konseling"
                                        class="accent-[#0970A5]"
                                        {{ in_array('Konseling', $tindakLanjut) ? 'checked' : '' }}
                                    >
                                    Konseling
                                </label>

                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="tindak_lanjut[]"
                                        value="Pendampingan"
                                        class="accent-[#0970A5]"
                                        {{ in_array('Pendampingan', $tindakLanjut) ? 'checked' : '' }}
                                    >
                                    Pendampingan
                                </label>

                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="tindak_lanjut[]"
                                        value="Sanksi Administratif"
                                        class="accent-[#0970A5]"
                                        {{ in_array('Sanksi Administratif', $tindakLanjut) ? 'checked' : '' }}
                                    >
                                    Sanksi Administratif
                                </label>

                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="tindak_lanjut[]"
                                        value="Rujukan Lanjutan"
                                        class="accent-[#0970A5]"
                                        {{ in_array('Rujukan Lanjutan', $tindakLanjut) ? 'checked' : '' }}
                                    >
                                    Rujukan Lanjutan
                                </label>

                            </div>


                        </div>

                        <!-- Catatan Tindak Lanjut -->
                        <div class="flex flex-col mb-6">
                            <label class="text-gray-600 font-medium mb-1">
                                Catatan Tindak Lanjut
                                <span class="text-gray-400 text-sm">(Opsional)</span>
                            </label>
                            <textarea name="catatan_tindak_lanjut" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none" rows="3" placeholder="Tulis catatan singkat tindak lanjut...">{{ old('catatan_tindak_lanjut', $investigasi->catatan_tindak_lanjut ?? '') }}</textarea>
                        </div>

                        <!-- Hasil Akhir -->
                        <div class="flex flex-col mb-6">
                            <label class="text-gray-600 font-medium mb-1">
                                Hasil Akhir Investigasi
                            </label>
                            @php
                                $hasilAkhir = old(
                                    'hasil_akhir',
                                    $investigasi->hasil_akhir ?? ''
                                );
                            @endphp

                            <select
                                name="hasil_akhir"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2
                                    focus:outline-none focus:ring-2 focus:ring-[#0970A5] bg-white"
                            >

                                <option value="">-- Pilih hasil akhir --</option>

                                <option value="Terbukti" {{ $hasilAkhir === 'Terbukti' ? 'selected' : '' }}>
                                    Terbukti
                                </option>

                                <option value="Sebagian Terbukti" {{ $hasilAkhir === 'Sebagian Terbukti' ? 'selected' : '' }}>
                                    Sebagian Terbukti
                                </option>

                                <option value="Tidak Terbukti" {{ $hasilAkhir === 'Tidak Terbukti' ? 'selected' : '' }}>
                                    Tidak Terbukti
                                </option>

                            </select>


                        </div>

                        <!-- Kesimpulan Investigasi -->
                        <div class="flex flex-col">
                            <label class="text-gray-600 font-medium mb-1">
                                Kesimpulan Investigasi
                            </label>
                            <textarea name="kesimpulan" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0970A5] resize-none"
                                rows="4"
                                placeholder="Tulis kesimpulan akhir berdasarkan hasil investigasi..."
                            >{{ old('kesimpulan', $investigasi->kesimpulan ?? '') }}</textarea>
                        </div>
                    </div>


                  <div class="flex justify-end m-2 gap-3">
                      <!-- Draft -->
                      <button type="submit" name="status_investigasi" value="draft"
                          class="px-6 py-3 rounded-lg bg-gray-400 text-white font-semibold hover:bg-gray-500 shadow-md hover:shadow-lg transition">
                          Simpan Draft
                      </button>

                      <!-- Publish -->
                      <button type="submit" name="status_investigasi" value="publish"
                          class="px-6 py-3 rounded-lg bg-[#0970A5] text-white font-semibold hover:bg-[#085d88] shadow-md hover:shadow-lg transition">
                          Investigasi
                      </button>
                  </div>


                    </form>  
                </div>
            </div>
        </div>
    </div>
</body>
</html>
