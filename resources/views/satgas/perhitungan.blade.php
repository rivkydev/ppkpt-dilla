<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
</head>
<body class="h-screen ">
    <x-nav-baar></x-nav-baar>
    
    <div class="flex mt-31">
        <div class="flex-1 bg-[#E0DEDE] rounded-lg shadow-lg lg:mx-4 mx-2 p-5 overflow-y-auto">
            <div x-data="stepPage" class="max-w-6xl mx-auto">
                

                <!-- Header -->
                <div class="bg-[#0970A5] text-white rounded-xl p-6 mb-6 shadow-lg">
                    <h1 class="text-2xl font-bold text-center flex items-center justify-center gap-3">
                        <i class="fa-solid fa-calculator"></i>
                        Proses Perhitungan SPK
                    </h1>
                    <p class="text-center mt-2 text-blue-100">Sistem Pendukung Keputusan dengan Metode Grey AHP - MARCOS</p>
                </div>

                <!-- Step Indicator -->
                <div class="flex justify-between items-center mb-8 px-4">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="flex items-center">
                            
                            <!-- BULAT STEP -->
                            <div 
                                class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition"
                                :class="step === {{ $i }} 
                                    ? 'bg-[#0970A5] text-white' 
                                    : 'bg-gray-300 text-gray-600'">
                                {{ $i }}
                            </div>

                            <!-- GARIS -->
                            @if($i < 5)
                                <div 
                                    class="w-16 h-1 mx-2 transition"
                                    :class="step > {{ $i }} 
                                        ? 'bg-[#0970A5]' 
                                        : 'bg-gray-300'">
                                </div>
                            @endif

                        </div>
                    @endfor
                </div>

                <!-- Content Area -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 min-h-[400px]">
                    
                    <!-- Step 1 -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" 
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-[#0970A5] mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-table"></i>
                                Step 1 – Uji Konsistensi Pakar 1
                            </h2>
                            
                            <div class="rounded-lg p-4 mb-4 flex flex-col md:flex-row gap-6">

                                <!-- KIRI: Matriks Perbandingan -->
                                <div class="flex-1 overflow-x-auto">
                                    <h3 class="font-semibold mb-3">Matriks Perbandingan Kriteria :</h3>
                                    <div class="overflow-x-auto rounded-lg shadow-md">
                                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                            <thead>
                                                <tr class="bg-[#0970A5] text-white">
                                                    <th class="border px-3 py-2"></th> <!-- header kosong di kiri atas -->
                                                    <th class="border px-3 py-2">C1</th>
                                                    <th class="border px-3 py-2">C2</th>
                                                    <th class="border px-3 py-2">C3</th>
                                                    <th class="border px-3 py-2">C4</th>
                                                    <th class="border px-3 py-2">C5</th>
                                                    <th class="border px-3 py-2">C6</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($matriksperbandingan1 as $i => $row)
                                                <tr class="hover:bg-gray-100">
                                                    <!-- Label baris -->
                                                    <td class="border bg-[#0970A5] text-white px-3 py-2 font-semibold text-center">
                                                        C{{ $i+1 }}
                                                    </td>
                                                    <!-- Nilai matriks -->
                                                    @foreach($row as $value)
                                                        <td class="border px-3 py-2 text-center">
                                                            {{ number_format($value, 2) }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                                                        </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- KANAN: Vektor Awal W^0 -->
                                <div class="w-32 flex-shrink-0">
                                    <h3 class="font-semibold mb-3">Vektor Awal W⁰:</h3>
                                    <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                        <thead>
                                            <tr class="bg-[#0970A5] text-white">
                                                <th class="border px-3 py-2">Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 6; $i++)
                                                <tr class="hover:bg-gray-100">
                                                    <td class="border px-3 py-2 text-center">1</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <h3>Iterasi W sampai stabil:</h3>
                            @foreach($weights_history as $k => $w)
                                <p>W{{ $k+1 }} = [@foreach($w as $wi) {{ number_format($wi, 4) }}@if(!$loop->last), @endif @endforeach]</p>
                            @endforeach

                            <h3>Vektor Akhir (stabil):</h3>
                            <p>[ @foreach($weights_crisp as $wi) {{ number_format($wi, 4) }}@if(!$loop->last), @endif @endforeach ]</p>
                            <p>λ max akhir: {{ number_format($lambda_max, 4) }}, CI: {{ number_format($CI,4) }}, CR: {{ number_format($CR,4) }}</p>

                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" 
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-[#0970A5] mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-sync"></i>
                                Step 2 – Uji Konsistensi Pakar 2
                            </h2>
                            
                            <div class="rounded-lg p-4 mb-4 flex flex-col md:flex-row gap-6">

                                <!-- KIRI: Matriks Perbandingan -->
                                <div class="flex-1 overflow-x-auto">
                                    <h3 class="font-semibold mb-3">Matriks Perbandingan Kriteria :</h3>
                                    <div class="overflow-x-auto rounded-lg shadow-md">
                                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                            <thead>
                                                <tr class="bg-[#0970A5] text-white">
                                                    <th class="border px-3 py-2"></th> <!-- header kosong di kiri atas -->
                                                    <th class="border px-3 py-2">C1</th>
                                                    <th class="border px-3 py-2">C2</th>
                                                    <th class="border px-3 py-2">C3</th>
                                                    <th class="border px-3 py-2">C4</th>
                                                    <th class="border px-3 py-2">C5</th>
                                                    <th class="border px-3 py-2">C6</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($matriksperbandingan2 as $i => $row)
                                                <tr class="hover:bg-gray-100">
                                                    <!-- Label baris -->
                                                    <td class="border bg-[#0970A5] text-white px-3 py-2 font-semibold text-center">
                                                        C{{ $i+1 }}
                                                    </td>
                                                    <!-- Nilai matriks -->
                                                    @foreach($row as $value)
                                                        <td class="border px-3 py-2 text-center">
                                                            {{ number_format($value, 2) }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                                                        </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- KANAN: Vektor Awal W^0 -->
                                <div class="w-32 flex-shrink-0">
                                    <h3 class="font-semibold mb-3">Vektor Awal W⁰:</h3>
                                    <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                        <thead>
                                            <tr class="bg-[#0970A5] text-white">
                                                <th class="border px-3 py-2">Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 6; $i++)
                                                <tr class="hover:bg-gray-100">
                                                    <td class="border px-3 py-2 text-center">1</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <h3>Iterasi W sampai stabil:</h3>
                            @foreach($weights_history2 as $k => $w)
                                <p>W{{ $k+1 }} = [@foreach($w as $wi) {{ number_format($wi, 4) }}@if(!$loop->last), @endif @endforeach]</p>
                            @endforeach

                            <h3>Vektor Akhir (stabil):</h3>
                            <p>[ @foreach($weights_crisp2 as $wi) {{ number_format($wi, 4) }}@if(!$loop->last), @endif @endforeach ]</p>
                            <p>λ max akhir: {{ number_format($lambda_max2, 4) }}, CI: {{ number_format($CI2,4) }}, CR: {{ number_format($CR2,4) }}</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" 
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-[#0970A5] mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-sync"></i>
                                Step 3 – Uji Konsistensi Pakar 3
                            </h2>
                            
                            <div class="rounded-lg p-4 mb-4 flex flex-col md:flex-row gap-6">

                                <!-- KIRI: Matriks Perbandingan -->
                                <div class="flex-1 overflow-x-auto">
                                    <h3 class="font-semibold mb-3">Matriks Perbandingan Kriteria :</h3>
                                    <div class="overflow-x-auto rounded-lg shadow-md">
                                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                            <thead>
                                                <tr class="bg-[#0970A5] text-white">
                                                    <th class="border px-3 py-2"></th> <!-- header kosong di kiri atas -->
                                                    <th class="border px-3 py-2">C1</th>
                                                    <th class="border px-3 py-2">C2</th>
                                                    <th class="border px-3 py-2">C3</th>
                                                    <th class="border px-3 py-2">C4</th>
                                                    <th class="border px-3 py-2">C5</th>
                                                    <th class="border px-3 py-2">C6</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($matriksperbandingan3 as $i => $row)
                                                <tr class="hover:bg-gray-100">
                                                    <!-- Label baris -->
                                                    <td class="border bg-[#0970A5] text-white px-3 py-2 font-semibold text-center">
                                                        C{{ $i+1 }}
                                                    </td>
                                                    <!-- Nilai matriks -->
                                                    @foreach($row as $value)
                                                        <td class="border px-3 py-2 text-center">
                                                            {{ number_format($value, 2) }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                                                        </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- KANAN: Vektor Awal W^0 -->
                                <div class="w-32 flex-shrink-0">
                                    <h3 class="font-semibold mb-3">Vektor Awal W⁰:</h3>
                                    <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                        <thead>
                                            <tr class="bg-[#0970A5] text-white">
                                                <th class="border px-3 py-2">Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 6; $i++)
                                                <tr class="hover:bg-gray-100">
                                                    <td class="border px-3 py-2 text-center">1</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <h3>Iterasi W sampai stabil:</h3>
                            @foreach($weights_history3 as $k => $w)
                                <p>W{{ $k+1 }} = [@foreach($w as $wi) {{ number_format($wi, 4) }}@if(!$loop->last), @endif @endforeach]</p>
                            @endforeach

                            <h3>Vektor Akhir (stabil):</h3>
                            <p>[ @foreach($weights_crisp3 as $wi) {{ number_format($wi, 4) }}@if(!$loop->last), @endif @endforeach ]</p>
                            <p>λ max akhir: {{ number_format($lambda_max3, 4) }}, CI: {{ number_format($CI3,4) }}, CR: {{ number_format($CR3,4) }}</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div x-show="step === 4"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0">

                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-[#0970A5] mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-weight"></i>
                                Step 4 – Metode Grey AHP
                            </h2>

                            <div class="grid md:grid-cols-2 gap-6">

                                <!-- ======================= -->
                                <!-- GREY PAKAR 1 (KIRI) -->
                                <!-- ======================= -->
                                <div class="bg-gray-50 rounded-lg p-4 shadow">
                                    <h3 class="font-semibold mb-3 text-center text-[#0970A5]">
                                        Matriks Perbandingan Grey Pakar 1
                                    </h3>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                            <thead>
                                                <tr class="bg-[#0970A5] text-white">
                                                    <th class="border px-2 py-2"></th>
                                                    @for($i=1;$i<=6;$i++)
                                                        <th class="border px-2 py-2">C{{ $i }}</th>
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($matriksgrey1 as $i => $row)
                                                    <tr class="hover:bg-gray-100 text-center">
                                                        <td class="border bg-[#0970A5] text-white font-semibold">
                                                            C{{ $i+1 }}
                                                        </td>

                                                        @foreach($row as $interval)
                                                            <td class="border px-1 py-2">
                                                                [{{ number_format($interval[0],2) }},
                                                                {{ number_format($interval[1],2) }}]
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <!-- ======================= -->
                                <!-- GREY PAKAR 2 (KANAN) -->
                                <!-- ======================= -->
                                <div class="bg-gray-50 rounded-lg p-4 shadow">
                                    <h3 class="font-semibold mb-3 text-center text-[#0970A5]">
                                        Matriks Perbandingan Grey Pakar 2
                                    </h3>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                            <thead>
                                                <tr class="bg-[#0970A5] text-white">
                                                    <th class="border px-2 py-2"></th>
                                                    @for($i=1;$i<=6;$i++)
                                                        <th class="border px-2 py-2">C{{ $i }}</th>
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($matriksgrey2 as $i => $row)
                                                    <tr class="hover:bg-gray-100 text-center">
                                                        <td class="border bg-[#0970A5] text-white font-semibold">
                                                            C{{ $i+1 }}
                                                        </td>

                                                        @foreach($row as $interval)
                                                            <td class="border px-1 py-2">
                                                                [{{ number_format($interval[0],2) }},
                                                                {{ number_format($interval[1],2) }}]
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- ======================= -->
                                <!-- GREY PAKAR 3 (KANAN) -->
                                <!-- ======================= -->
                                <div class="bg-gray-50 rounded-lg p-4 shadow">
                                    <h3 class="font-semibold mb-3 text-center text-[#0970A5]">
                                        Matriks Perbandingan Grey Pakar 3
                                    </h3>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                            <thead>
                                                <tr class="bg-[#0970A5] text-white">
                                                    <th class="border px-2 py-2"></th>
                                                    @for($i=1;$i<=6;$i++)
                                                        <th class="border px-2 py-2">C{{ $i }}</th>
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($matriksgrey3 as $i => $row)
                                                    <tr class="hover:bg-gray-100 text-center">
                                                        <td class="border bg-[#0970A5] text-white font-semibold">
                                                            C{{ $i+1 }}
                                                        </td>

                                                        @foreach($row as $interval)
                                                            <td class="border px-1 py-2">
                                                                [{{ number_format($interval[0],2) }},
                                                                {{ number_format($interval[1],2) }}]
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <h3 class="font-semibold mt-6 mb-2">Matriks Agregasi (Geometric Mean)</h3>
                            <table class="min-w-full border text-sm">
                                <thead>
                                    <tr class="bg-[#0970A5] text-white">
                                        <th class="border px-2 py-2"></th>
                                        @for($i=1;$i<=6;$i++)
                                        <th class="border px-2 py-2">C{{ $i }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                            @foreach($greyAggregated as $i => $row)
                            <tr>
                                <td class="border bg-[#0970A5] text-white font-semibold">
                                    C{{ $i+1 }}
                                </td>
                                @foreach($row as $interval)
                                    <td class="border px-2 py-1 text-center">
                                        [{{ number_format($interval[0],3) }},
                                        {{ number_format($interval[1],3) }}]
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                            </table>

                            <h3 class="font-semibold mt-6 mb-2">Matriks Defuzzifikasi</h3>
                            <table class="min-w-full border text-sm">
                                <thead>
                                    <tr class="bg-[#0970A5] text-white">
                                        <th class="border px-2 py-2"></th>
                                        @for($i=1;$i<=6;$i++)
                                        <th class="border px-2 py-2">C{{ $i }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                            @foreach($greyDefuzzified as $i => $row)
                            <tr>
                                <td class="border bg-[#0970A5] text-white font-semibold text-center">
                                    C{{ $i+1 }}
                                </td>
                                @foreach($row as $value)
                                    <td class="border px-2 py-1 text-center">
                                        {{ number_format($value,4) }}
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                            </table>

                            <h3>Iterasi W sampai stabil:</h3>
                            @foreach($greyEigen as $k => $w)
                                <p>W{{ $k+1 }} = [@foreach($w as $wi) {{ number_format($wi, 4) }}@if(!$loop->last), @endif @endforeach]</p>
                            @endforeach
                            <h3>Bobot Grey:</h3>
                            <p>[ @foreach($greyWeights as $wi) {{ number_format($wi, 4) }}@if(!$loop->last), @endif @endforeach ]</p>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div x-show="step === 5" x-transition:enter="transition ease-out duration-300" 
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-[#0970A5] mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-trophy"></i>
                                Step 5 – Metode MARCOS
                            </h2>

                        <div class="flex-1 overflow-x-auto">
                            <h3 class="font-semibold mb-3 text-lg">Matriks Alternatif</h3>

                            <div class="overflow-x-auto rounded-lg shadow-md">
                                <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                    <thead>
                                        <tr class="bg-[#0970A5] text-white">
                                            <th class="border px-3 py-2"></th>
                                            <th class="border px-3 py-2">C1</th>
                                            <th class="border px-3 py-2">C2</th>
                                            <th class="border px-3 py-2">C3</th>
                                            <th class="border px-3 py-2">C4</th>
                                            <th class="border px-3 py-2">C5</th>
                                            <th class="border px-3 py-2">C6</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($matriksAlternatif as $i => $row)
                                            <tr class="hover:bg-gray-100">
                                                <td class="border bg-[#0970A5] text-white px-3 py-2 font-semibold text-center">
                                                    A{{ $i+1 }}
                                                </td>

                                                @foreach($row as $value)
                                                    <td class="border px-3 py-2 text-center">
                                                        {{ number_format($value, 4) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Anti Ideal -->
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2 text-md">Solusi Anti Ideal (AAI)</h4>
                            <table class="w-full border-collapse border border-gray-300 text-sm">
                                <tr class="bg-gray-100">
                                    @foreach($AAI as $val)
                                        <td class="border px-3 py-2 text-center">
                                            {{ number_format($val,4) }}
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        </div>

                        <!-- Ideal -->
                        <div>
                            <h4 class="font-semibold mb-2 text-md">Solusi Ideal (AI)</h4>
                            <table class="w-full border-collapse border border-gray-300 text-sm">
                                <tr class="bg-gray-100">
                                    @foreach($AI as $val)
                                        <td class="border px-3 py-2 text-center">
                                            {{ number_format($val,4) }}
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        </div>
<div class="mt-8">
    <h3 class="font-semibold mb-3 text-lg">Matriks Normalisasi (N)</h3>

    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full border-collapse border border-gray-300 text-sm">
            <tbody>
                @foreach($N as $i => $row)
                    <tr class="hover:bg-gray-100">
                        <td class="border px-3 py-2 text-center font-semibold">
                            @if($i == 0)
                                AAI
                            @elseif($i == count($N)-1)
                                AI
                            @else
                                A{{ $i }}
                            @endif
                        </td>

                        @foreach($row as $val)
                            <td class="border px-3 py-2 text-center">
                                {{ number_format($val,4) }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8">
    <h3 class="font-semibold mb-3 text-lg">Normalisasi Berbobot (WN)</h3>

    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full border-collapse border border-gray-300 text-sm">
            <tbody>
                @foreach($WN as $i => $row)
                    <tr class="hover:bg-gray-100">
                        <td class="border px-3 py-2 text-center font-semibold">
                            @if($i == 0)
                                AAI
                            @elseif($i == count($WN)-1)
                                AI
                            @else
                                A{{ $i }}
                            @endif
                        </td>

                        @foreach($row as $val)
                            <td class="border px-3 py-2 text-center">
                                {{ number_format($val,4) }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8">
    <h3 class="font-semibold mb-3 text-lg">Nilai Kegunaan (S)</h3>

    @foreach($S_all as $i => $val)
        <div class="mb-1">
            @if($i == 0)
                <strong>AAI :</strong>
            @elseif($i == count($S_all)-1)
                <strong>AI :</strong>
            @else
                <strong>A{{ $i }} :</strong>
            @endif

            {{ number_format($val,4) }}
        </div>
    @endforeach
</div>

<div class="mt-8">
    <h3 class="font-semibold mb-3 text-lg">Derajat Kegunaan</h3>

    @foreach($Cplus as $i => $val)
        <div>C+ A{{ $i+1 }} = {{ number_format($val,4) }}</div>
    @endforeach

    <hr class="my-3">

    @foreach($Cminus as $i => $val)
        <div>C- A{{ $i+1 }} = {{ number_format($val,4) }}</div>
    @endforeach
</div>

<div class="mt-8">
    <h3 class="font-semibold mb-3 text-lg">Ranking Akhir MARCOS</h3>

    @foreach($ranking as $i => $val)
        <div>
            Alternatif {{ $i+1 }} = 
            <strong>{{ number_format($val,4) }}</strong>
        </div>
    @endforeach
</div>
                            

                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('satgas.laporanmasuk') }}" 
                       class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </a>

                    <div class="flex items-center gap-4">
                        <button @click="prevStep" 
                                :disabled="step===1"
                                class="px-6 py-3 bg-gray-300 rounded-lg hover:bg-gray-400 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center gap-2">
                            <i class="fa-solid fa-chevron-left"></i>
                            Prev
                        </button>
                        
                        <div class="bg-[#0970A5] text-white px-4 py-2 rounded-lg font-semibold">
                            Step <span x-text="step"></span> / <span x-text="totalSteps"></span>
                        </div>
                        
                        <button @click="nextStep" 
                                :disabled="step===totalSteps"
                                class="px-6 py-3 bg-[#0970A5] text-white rounded-lg hover:bg-[#065a84] disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center gap-2">
                            Next
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
