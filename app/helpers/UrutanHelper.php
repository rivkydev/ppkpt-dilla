<?php

if (!function_exists('terbilang')) {
    function terbilang($angka)
    {
        $angka = abs($angka);

        $huruf = [
            "",
            "satu",
            "dua",
            "tiga",
            "empat",
            "lima",
            "enam",
            "tujuh",
            "delapan",
            "sembilan",
            "sepuluh",
            "sebelas"
        ];

        if ($angka < 12) {
            return $huruf[$angka];
        } elseif ($angka < 20) {
            return terbilang($angka - 10) . " belas";
        } elseif ($angka < 100) {
            return terbilang(intval($angka / 10)) . " puluh " . terbilang($angka % 10);
        } elseif ($angka < 200) {
            return "seratus " . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return terbilang(intval($angka / 100)) . " ratus " . terbilang($angka % 100);
        } else {
            return "angka terlalu besar";
        }
    }
}

if (!function_exists('angkaKeUrutan')) {
    function angkaKeUrutan($angka)
    {
        if ($angka == 1) return "pertama";
        if ($angka == 2) return "kedua";
        if ($angka == 3) return "ketiga";
        if ($angka == 11) return "kesebelas";

        return "ke" . terbilang($angka);
    }
}