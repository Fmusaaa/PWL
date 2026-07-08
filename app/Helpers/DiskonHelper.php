<?php


if (!function_exists('hitung_biaya_jasa')) {
    function hitung_biaya_jasa($total_harga)
    {
        $total_harga = (float) $total_harga;
        if ($total_harga <= 10000000) {
            $biaya_jasa = $total_harga * 0.01;
        } else {
            $biaya_jasa = $total_harga * 0.02;
        }

        return (float) $biaya_jasa;
    }
}

if (!function_exists('hitung_persen_voucher')) {
    function hitung_persen_voucher($voucher_code)
    {
        $voucher_code = strtoupper(trim((string) $voucher_code));

        $daftar_voucher = [
            'PROMO2025' => 10,
            'PROMO2026' => 15,
            'AKHIRTAHUN' => 25,
        ];

        return $daftar_voucher[$voucher_code] ?? 0;
    }
}

if (!function_exists('hitung_diskon_voucher')) {
    function hitung_diskon_voucher($total_harga, $voucher_code) {
        $persen = 0;

        $kode = strtoupper(trim($voucher_code));

        if ($kode == 'PROMO2025') {
            $persen = 0.10; // 10%
        } elseif ($kode == 'PROMO2026') {
            $persen = 0.15; // 15%
        } elseif ($kode == 'AKHIRTAHUN') {
            $persen = 0.25; // 25%
        }

        return $total_harga * $persen;
    }
}

if (!function_exists('hitung_free_mouse')) {
    function hitung_free_mouse($total_harga) {
        if ($total_harga > 15000000) {
            return 150000;
        }
        return 0;
    }
}
