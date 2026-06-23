<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use App\Models\Alternatif;
use App\Services\GreyAHPService;
use App\Services\MARCOSService;


class AhpController extends Controller
{
public function calculate()
{
    
    $crispAHP = [
        // C1
        [1,   7,   3,   5,   5,   5],
        // C2
        [1/7, 1,   1/3,   1/5,   3,   1],
        // C3
        [1/3, 3, 1,   1/3,   3,   1],
        // C4
        [1/5, 5, 3, 1,   5,   3],
        // C5
        [1/5,   1/3, 1/3, 1/5,   1,   1/3],
        // C6
        [1/5, 1,   1,   1/3,   3,   1],
    ];

    $crispAHP1 = [

    // C1 – Tingkat Ketidaklengkapan Data Laporan
    [1, 9, 9, 9, 9, 9],

    // C2 – Tingkat Keparahan Kekerasan
    [1/9, 1, 1/3, 1/3, 1/7, 1],

    // C3 – Risiko Ancaman Berulang
    [1/9, 3, 1, 1, 1/3, 3],

    // C4 – Kerentanan Korban
    [1/9, 3, 1, 1, 1/5, 3],

    // C5 – Bukti Pendukung
    [1/9, 7, 3, 5, 1, 7],

    // C6 – Dampak Sosial / Publik
    [1/9, 1, 1/3, 1/3, 1/7, 1],

    ];

    $crispAHP2 = [

    [1, 1, 5, 1, 1, 1/3],
    [1, 1, 3, 1, 1/7, 1/5],
    [1/5, 1/3, 1, 1/5, 1/7, 1/7],
    [1, 1, 5, 1, 1, 1],
    [1, 7, 7, 1, 1, 1/3],
    [3, 5, 7, 1, 3, 1]
    ];


    $judgements = [[
        // C1
        [[1,2],[6,8],[2,4],[4,6],[4,6],[4,6]],

        // C2
        [[1/8,1/6],[1,2],[1/4,1/2],[1/6,1/4],[2,4],[1,2]],

        // C3
        [[1/4,1/2],[2,4],[1,2],[1/4,1/2],[2,4],[1,2]],

        // C4
        [[1/6,1/4],[4,6],[2,4],[1,2],[4,6],[2,4]],

        // C5
        [[1/6,1/4],[1/4,1/2],[1/4,1/2],[1/6,1/4],[1,2],[1/4,1/2]],

        // C6
        [[1/6,1/4],[1,2],[1,2],[1/4,1/2],[2,4],[1,2]],
    ], [

    // C1
    [[1,2],[8,10],[8,10],[8,10],[8,10],[8,10]],

    // C2
    [[1/10,1/8], [1,2], [1/4,1/2], [1/4,1/2], [1/8,1/6], [1,2]],

    // C3
    [[1/10,1/8], [2,4], [1,2], [1,2], [1/4,1/2], [2,4]],

    // C4
    [[1/10,1/8], [2,4], [1,2], [1,2], [1/6,1/4], [2,4]],

    // C5
    [[1/10,1/8], [6,8], [2,4], [4,6], [1,2], [6,8]],

    // C6
    [[1/10,1/8], [1,2], [1/4,1/2], [1/4,1/2], [1/8,1/6], [1,2]],

], [
    [[1,2],[1,2],[4,6],[1,2],[1,2],[1/4,1/2]],
    [[1,2],[1,2],[2,4],[1,2],[1/8,1/6],[1/6,1/4]],
    [[1/6,1/4],[1/4,1/2],[1,2],[1/6,1/4],[1/8,1/6],[1/8,1/6]],
    [[1,2],[1,2],[4,6],[1,2],[1,2],[1,2]],
    [[1,2],[6,8],[6,8],[1,2],[1,2],[1/4,1/2]],
    [[2,4],[4,6],[6,8],[1,2],[2,4],[1,2]]
]];

$alternatif = Alternatif::with(['aduan.lastStatus'])
    ->whereHas('aduan.lastStatus', function ($q) {
        $q->where('label2', 'Diteruskan Ke Satgas')

        // ⬇ hanya yang BELUM diinvestigasi
        ->where(function ($qq) {
            $qq->whereNull('label3')
               ->orWhere('label3', '!=', 'Investigasi Lapangan');
        })

        ->where(function ($qq) {
            $qq->whereNull('label4')
               ->orWhere('label4', '!=', 'Laporan Selesai');
        });
    })
    ->orderBy('aduan_id')
    ->get();


$matriksAlternatif = [];

foreach ($alternatif as $alt) {
    $matriksAlternatif[] = [
        $alt->kriteria1,
        $alt->kriteria2,
        $alt->kriteria3,
        $alt->kriteria4,
        $alt->kriteria5,
        $alt->kriteria6,
    ];
}

$service = new \App\Services\GreyAHPService();

// Hitung Pakar 1
$result1 = $service->process($crispAHP);
// Hitung Pakar 2
$result2 = $service->process($crispAHP1);

$result3 = $service->process($crispAHP2);

$greyResult = $service->processGrey($judgements);

// Bobot akhir (misal dari pakar 1 atau agregasi grey)
$bobotAkhir = $result1['weights_crisp'];
$bobotAkhir1 = $result2['weights_crisp'];
$bobotAkhir2 = $result3['weights_crisp'];

$type = [
        'cost',    // C1
        'benefit', // C2
        'benefit', // C3
        'benefit', // C4
        'benefit', // C5
        'benefit', // C6
        ];
$type = array_values($type);



Bobot::updateOrCreate(
    ['id' => 1],
    [
        'C1' => $greyResult['weights'][0],
        'C2' => $greyResult['weights'][1],
        'C3' => $greyResult['weights'][2],
        'C4' => $greyResult['weights'][3],
        'C5' => $greyResult['weights'][4],
        'C6' => $greyResult['weights'][5],
    ]
);

// Check if there are alternatives to process
if (empty($matriksAlternatif)) {
    return view('satgas.perhitungan', [
        'matriksperbandingan1' => $crispAHP,
        'matriksperbandingan2' => $crispAHP1,   
        'matriksperbandingan3' => $crispAHP2, 
        'weights_history' => $result1['weights_history'],
        'weights_history2' => $result2['weights_history'], 
        'weights_history3' => $result3['weights_history'], 
        'weights_crisp' => $bobotAkhir,
        'weights_crisp2' => $bobotAkhir1,    
        'weights_crisp3' => $bobotAkhir2,               
        'lambda_max' => $result1['lambda_max'],
        'CI' => $result1['CI'],
        'CR' => $result1['CR'],
        'CR2' => $result2['CR'],
        'CI2' => $result2['CI'],
        'CR3' => $result3['CR'],
        'CI3' => $result3['CI'],
        'lambda_max2' => $result2['lambda_max'],
        'lambda_max3' => $result3['lambda_max'],
        'matriksgrey1' => $judgements[0],
        'matriksgrey2' => $judgements[1],
        'matriksgrey3' => $judgements[2],
        'greyAggregated'  => $greyResult['aggregated'],
        'greyDefuzzified' => $greyResult['defuzzified'],
        'greyEigen'       => $greyResult['eigenvector'],
        'greyWeights'     => $greyResult['weights'],
        'alternatif' => $alternatif,
        'matriksAlternatif' => $matriksAlternatif,
        'AI' => [],
        'AAI' => [],
        'L_ext' => [],
        'N' => [],
        'WN' => [],
        'S_all' => [],
        'Cplus' => [],
        'Cminus' => [],
        'ranking' => [],
        'no_data' => true,
    ]);
}

$marcos = new MARCOSService();

// Step 2
[$AI, $AAI] = $marcos->idealAntiIdeal($matriksAlternatif, $type);

// Step 3 — Extended matrix
$L_ext = array_merge([$AAI], $matriksAlternatif, [$AI]);

// Step 4
$N  = $marcos->normalisasi($L_ext, $AI, $type);
$WN = $marcos->normalisasiBerbobot($N, $greyResult['weights']);

// Step 5
$S_all = $marcos->nilaiKegunaan($WN);

// Step 6
[$Cplus, $Cminus] = $marcos->derajatKegunaan($S_all, count($matriksAlternatif));

// Step 7
$ranking = $marcos->fungsiKegunaan($Cplus, $Cminus);


return view('satgas.perhitungan', [
    'matriksperbandingan1' => $crispAHP,
    'matriksperbandingan2' => $crispAHP1,    
    'matriksperbandingan3' => $crispAHP2,    
    'weights_history' => $result1['weights_history'],
    'weights_history2' => $result2['weights_history'], 
    'weights_history3' =>$result3['weights_history'],
    'weights_crisp' => $bobotAkhir,
    'weights_crisp2' => $bobotAkhir1,    
    'weights_crisp3' => $bobotAkhir2,               
    'lambda_max' => $result1['lambda_max'],
    'CI' => $result1['CI'],
    'CR' => $result1['CR'],
    'CR2' => $result2['CR'],
    'CI2' => $result2['CI'],
    'CR3' => $result3['CR'],
    'CI3' => $result3['CI'],
    'lambda_max2' => $result2['lambda_max'],
    'lambda_max3' => $result3['lambda_max'],
    'matriksgrey1' => $judgements[0],
    'matriksgrey2' => $judgements[1],
    'matriksgrey3' => $judgements[2],
    'greyAggregated'  => $greyResult['aggregated'],
    'greyDefuzzified' => $greyResult['defuzzified'],
    'greyEigen'       => $greyResult['eigenvector'],
    'greyWeights'     => $greyResult['weights'],
    'alternatif' => $alternatif,
    'matriksAlternatif' => $matriksAlternatif,
    'AI' => $AI,
    'AAI' => $AAI,
    'L_ext' => $L_ext,
    'N' => $N,
    'WN' => $WN,
    'S_all' => $S_all,
    'Cplus' => $Cplus,
    'Cminus' => $Cminus,
    'ranking' => $ranking,
]);
}

}
