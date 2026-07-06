<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class DiskonHelperTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        helper('DiskonHelper');
    }

    public function testBiayaJasaSesuaiBatasTotal(): void
    {
        $this->assertSame(62_990.0, hitung_biaya_jasa(6_299_000));
        $this->assertSame(100_000.0, hitung_biaya_jasa(10_000_000));
        $this->assertSame(275_960.0, hitung_biaya_jasa(13_798_000));
    }

    public function testDiskonVoucherSesuaiKodePromo(): void
    {
        $this->assertSame(10, hitung_persen_voucher('PROMO2025'));
        $this->assertSame(15, hitung_persen_voucher('promo2026'));
        $this->assertSame(25, hitung_persen_voucher(' AKHIRTAHUN '));
        $this->assertSame(0, hitung_persen_voucher('KODESALAH'));

        $this->assertSame(1_379_800.0, hitung_diskon_voucher(13_798_000, 'PROMO2025'));
        $this->assertSame(2_669_700.0, hitung_diskon_voucher(17_798_000, 'PROMO2026'));
        $this->assertSame(5_449_500.0, hitung_diskon_voucher(21_798_000, 'AKHIRTAHUN'));
        $this->assertSame(0.0, hitung_diskon_voucher(10_899_000, 'KODESALAH'));
    }

    public function testFreeMouseHanyaUntukTotalDiAtasLimaBelasJuta(): void
    {
        $this->assertSame(0.0, hitung_free_mouse(15_000_000));
        $this->assertSame(150_000.0, hitung_free_mouse(15_000_001));
    }

    public function testSubtotalPromoSesuaiRumusSoal(): void
    {
        $cases = [
            [6_299_000, '', 6_361_990.0],
            [13_798_000, 'PROMO2025', 12_694_160.0],
            [21_798_000, 'AKHIRTAHUN', 16_634_460.0],
            [17_798_000, 'PROMO2026', 15_334_260.0],
            // Rumus soal: total lebih dari 10 juta memakai biaya jasa 2%.
            [10_899_000, 'KODESALAH', 11_116_980.0],
        ];

        foreach ($cases as [$total, $voucher, $expected]) {
            $subtotalPromo = $total
                + hitung_biaya_jasa($total)
                - hitung_diskon_voucher($total, $voucher)
                - hitung_free_mouse($total);

            $this->assertSame($expected, $subtotalPromo);
        }
    }
}
