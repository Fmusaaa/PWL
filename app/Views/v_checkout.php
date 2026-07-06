<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

        <?= form_hidden('username', session()->get('username')) ?>

        <?= form_input([
            'type' => 'hidden',
            'name' => 'total_harga',
            'id' => 'total_harga'
        ]) ?>

        <div class="col-12">
            <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'nama',
                'id'       => 'nama',
                'class'    => 'form-control',
                'value'    => session()->get('username'),
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'alamat',
                'id'    => 'alamat',
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
            <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?>
            <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'ongkir',
                'id'       => 'ongkir',
                'class'    => 'form-control',
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'        => 'voucher_code',
                'id'          => 'voucher_code',
                'class'       => 'form-control',
                'placeholder' => 'PROMO2025 / PROMO2026 / AKHIRTAHUN'
            ]) ?>
            <small class="text-muted">Tersedia: PROMO2025 (10%), PROMO2026 (15%), AKHIRTAHUN (25%)</small>
        </div>
        <div class="col-12">
            <?= form_submit(
                'submit',
                'Buat Pesanan',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <?= form_close() ?>
    </div>
    <div class="col-lg-6">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($items)) :
                    foreach ($items as $index => $item) :
                ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                        </tr>
                <?php
                    endforeach;
                endif;
                ?>
                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal</td>
                    <td><?= number_to_currency($total, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-danger">Diskon Voucher</td>
                    <td class="text-danger">
                        <span id="diskon_voucher">-<?= number_to_currency($diskon_voucher, 'IDR') ?></span>
                        <br>
                        (<span id="voucher_persen"><?= $voucher_persen ?></span>%)
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Biaya Jasa</td>
                    <td><?= number_to_currency($biaya_jasa, 'IDR') ?></td>
                </tr>
                <?php if ($free_mouse > 0) : ?>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-success">Free Mouse</td>
                    <td class="text-success">-<?= number_to_currency($free_mouse, 'IDR') ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-primary">Subtotal Promo</td>
                    <td class="text-primary"><strong><span id="subtotal_promo"><?= number_to_currency($subtotal_promo, 'IDR') ?></span></strong></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td><strong>Grand Total (incl. Ongkir)</strong></td>
                    <td><strong><span id="total"><?= number_to_currency($subtotal_promo, 'IDR') ?></span></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        let ongkir = 0;
        let subtotal = <?= $total ?>;
        let biayaJasa = <?= $biaya_jasa ?>;
        let freeMouse = <?= $free_mouse ?>;
        let voucherPromo = {
            PROMO2025: 10,
            PROMO2026: 15,
            AKHIRTAHUN: 25
        };
        hitungTotal();

        function formatRupiah(nilai) {
            return `IDR ${Math.round(nilai).toLocaleString('en-US')}`;
        }

        function hitungTotal() {
            let kodeVoucher = $("#voucher_code").val().trim().toUpperCase();
            let voucherPersen = voucherPromo[kodeVoucher] || 0;
            let diskonVoucher = Math.round((voucherPersen / 100) * subtotal);
            let subtotalPromo = subtotal + biayaJasa - diskonVoucher - freeMouse;
            let total = subtotalPromo + ongkir;

            $("#ongkir").val(ongkir);
            $("#diskon_voucher").text(`-${formatRupiah(diskonVoucher)}`);
            $("#voucher_persen").text(voucherPersen);
            $("#subtotal_promo").text(formatRupiah(subtotalPromo));
            $("#total").text(formatRupiah(total));
            $("#total_harga").val(total);
        }

        $('#kelurahan').select2({
            placeholder: 'Cari daerah tujuan',
            minimumInputLength: 3,
            ajax: {
                url: '<?= site_url('ajax/destinations') ?>',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return data;
                },
                cache: true
            }
        });
        $("#kelurahan").on('change', function() {
            let id_kelurahan = $(this).val();

            $("#layanan").empty();
            ongkir = 0;
            hitungTotal();
            $.ajax({
                url: "<?= site_url('ajax/costs') ?>",
                dataType: "json",
                data: {
                    destination: id_kelurahan
                },
                success: function(data) {
                    data.forEach(function(item) {
                        $("#layanan").append(
                            $('<option>', {
                                value: item.cost,
                                text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                            })
                        );
                    });
                }
            });

            console.log(id_kelurahan);
        });

        $("#layanan").on('change', function() {
            ongkir = parseInt($(this).val(), 10) || 0;
            hitungTotal();
        });

        $("#voucher_code").on('keyup change', function() {
            hitungTotal();
        });
    });
</script>
<?= $this->endSection() ?>
