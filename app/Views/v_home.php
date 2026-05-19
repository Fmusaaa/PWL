<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

              <!-- Table with stripped rows -->
              <?php foreach ($products as $key => $item) : ?>         
        <img src="<?= base_url() . "img/" . $item['foto'] ?>" width="100"><br>
        <?= $item['nama'] ?><br>
        <?= $item['harga'] ?><br>         
<?php endforeach ?> 
              <!-- End Table with stripped rows -->
               <?= $this->endSection() ?>