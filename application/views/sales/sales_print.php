<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aurafoods Frozen</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css'); ?>">
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <div class="row">
      <div class="col-md-12">
        <?php if($total_sales != null) : ?>
        <div class="callout callout-info">
          <h6>Total penjualan <?= $month.'/'.$year ?></h6>
          <h3 class="text text-info"><strong><?= "Rp " . number_format($total_sales->total, 0, ",", ".") ?></strong></h3>
        </div>
        <?php endif ?>
        <br/>
        <?php if($records != null) : ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>ID</th>
              <th>Tanggal</th>
              <th>Subtotal</th>
              <th>Biaya Tambahan</th>
              <th>Grand Total</th>
              <th>Rekening</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; ?>
            <?php foreach ($records as $record) { ?>
            <?php
              if ($record->booking_status == 'waiting_confirmation' || $record->booking_status == 'checking_payment') {
                $color = 'warning';
              } else if ($record->booking_status == 'completed') {
                $color = 'secondary';
              } else if ($record->booking_status == 'canceled') {
                $color = 'danger';
              } else {
                $color = 'primary';
              }
            ?>
            <tr>
              <td><?= $no; ?></td>
              <td><?= $record->id ?></td>
              <td><?= $record->date ?></td>
              <td><?= "Rp " . number_format($record->subtotal, 0, ",", ".") ?></td>
              <td><?= "Rp " . number_format($record->other_cost, 0, ",", ".") ?></td>
              <td><?= "Rp " . number_format($record->grand_total, 0, ",", ".") ?></td>
              <td><?= "$record->bank_name - $record->account_number" ?></td>
            </tr>
            <?php $no++; } ?>
          </tbody>
        </table>
        <?php endif ?>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<!-- Page specific script -->
<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html>
