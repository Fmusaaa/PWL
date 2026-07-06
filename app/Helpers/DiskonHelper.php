<?php


if (!function_exists('hitung_biaya_jasa')) {
    function hitung_biaya_jasa($total_harga)
    {
        $total_harga = (float) $total_harga;
        $persen = $total_harga <= 10000000 ? 1 : 2;

        return (float) round(($persen / 100) * $total_harga);
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
    function hitung_diskon_voucher($total_harga, $voucher_code)
    {
        $total_harga = (float) $total_harga;
        $persen = hitung_persen_voucher($voucher_code);

        return (float) round(($persen / 100) * $total_harga);
    }
}

if (!function_exists('hitung_free_mouse')) {
    function hitung_free_mouse($total_harga)
    {
        return (float) $total_harga > 15000000 ? 150000.0 : 0.0;
    }
}
