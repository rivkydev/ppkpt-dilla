<?php

namespace App\Services;

class GreyAHPService
{
    /* ===============================
     * 1. AGREGASI GEOMETRIC MEAN
     * =============================== */
    public function aggregate(array $judgements): array
    {
        $H = count($judgements);
        $n = count($judgements[0]);
        $result = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $l = 1;
                $u = 1;
                for ($h = 0; $h < $H; $h++) {
                    $l *= $judgements[$h][$i][$j][0];
                    $u *= $judgements[$h][$i][$j][1];
                }
                $result[$i][$j] = [
                    pow($l, 1 / $H),
                    pow($u, 1 / $H)
                ];
            }
        }
        return $result;
    }

    /* ===============================
     * 2. DEFUZZIFIKASI
     * =============================== */
    public function defuzzify(array $greyMatrix): array
    {
        $crisp = [];
        foreach ($greyMatrix as $i => $row) {
            foreach ($row as $j => $interval) {
                [$l, $u] = $interval;
                $crisp[$i][$j] = ($l + $u) / 2;
            }
        }
        return $crisp;
    }

    /* ===============================
     * 3. HITUNG EIGENVECTOR
     * =============================== */
public function eigenvectorUntilStable(array $matrix, float $tolerance = 0.0001, int $maxIter = 1000): array
{
    $n = count($matrix);
    $w = array_fill(0, $n, 1);
    $history = [];

    for ($k = 0; $k < $maxIter; $k++) {
        $new = array_fill(0, $n, 0);

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $new[$i] += $matrix[$i][$j] * $w[$j];
            }
        }

        $sum = array_sum($new);
        for ($i = 0; $i < $n; $i++) {
            $new[$i] /= $sum;
        }

        $history[] = $new;

        // cek konvergensi
        $diff = 0;
        for ($i = 0; $i < $n; $i++) {
            $diff += abs($new[$i] - $w[$i]);
        }

        if ($diff < $tolerance) {
            break;
        }

        $w = $new;
    }

    return $history;
}

    /* ===============================
     * 4. LAMBDA MAX
     * =============================== */
    public function lambdaMax(array $matrix, array $weights): float
    {
        $n = count($matrix);
        $lambda = 0;

        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $lambda += $sum / $weights[$i];
        }
        return $lambda / $n;
    }

    /* ===============================
     * 5. CI & CR
     * =============================== */
    public function consistency(float $lambdaMax, int $n): array
    {
        $RI = [
            3 => 0.5245, 4 => 0.8815, 5 => 1.1086,
            6 => 1.2479, 7 => 1.3417, 8 => 1.4056,
            9 => 1.4499, 10 => 1.4854, 11 => 1.5141,
            12 => 1.5365, 13 => 1.5551, 14 => 1.5713,
            15 => 1.5838
        ];

        $CI = ($lambdaMax - $n) / ($n - 1);
        $CR = $CI / ($RI[$n] ?? 1);

        return compact('CI', 'CR');
    }


/* ===============================
 * 6. PIPELINE GREY AHP (SINGLE RESPONDENT)
 * =============================== */
public function process(array $crispAHP, float $tolerance = 0.0001, int $maxIter = 1000): array
{
    $n = count($crispAHP);

    // hitung eigenvector sampai stabil
    $weights_history = $this->eigenvectorUntilStable($crispAHP, $tolerance, $maxIter);

    // ambil vektor terakhir sebagai bobot akhir
    $weights_crisp = end($weights_history);

    // hitung lambda max & konsistensi
    $lambda = $this->lambdaMax($crispAHP, $weights_crisp);
    $consistency = $this->consistency($lambda, $n);

    return [
        'weights_history' => $weights_history,
        'weights_crisp' => $weights_crisp,
        'lambda_max' => $lambda,
        'CI' => $consistency['CI'],
        'CR' => $consistency['CR'],
    ];
}

public function processGrey(array $judgements): array
{
    if (empty($judgements)) {
        return [
            'aggregated' => [],
            'defuzzified' => [],
            'eigenvector' => [],
            'weights' => []
        ]; // aman kalau kosong
    }

    // Agregasi geometric mean
    $grey = $this->aggregate($judgements);

    // Defuzzifikasi
    $crispGrey = $this->defuzzify($grey);

    //Eigenvector sampai stabil
    $eigenHistory = $this->eigenvectorUntilStable($crispGrey);

    // Ambil iterasi terakhir (stabil)
    $finalEigen = end($eigenHistory);

    // 4️⃣ Normalisasi akhir (aman)
    $sumWeights = array_sum($finalEigen);
    $weights = [];

    if ($sumWeights > 0) {
        foreach ($finalEigen as $w) {
            $weights[] = $w / $sumWeights;
        }
    }

    return [
        'aggregated'   => $grey,
        'defuzzified'  => $crispGrey,
        'eigenvector'  => $eigenHistory,
        'weights'      => $weights
    ];
}


}
