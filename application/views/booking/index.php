  <style type="text/css">
    #example1 tbody tr {  
      cursor: pointer;
    }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?= $page_name; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?= $this->uri->segment(1); ?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data <?= $page_name; ?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-hover nowrap">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Keluhan</th>
                    <th>Tanggal</th>
                    <th>Subtotal</th>
                    <th>Biaya Tambahan</th>
                    <th>Grand Total</th>
                    <th>Status</th>
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
                      <tr class="clickable-row" onclick="window.location='<?= base_url("booking?id=$record->id") ?>'" class="clickable-row">
                        <td><?= $no; ?></td>
                        <td><?= $record->id ?></td>
                        <td><?= $record->user_name ?></td>
                        <td class="text-truncate" style="max-width: 150px;"><?= $record->complaint ?></td>
                        <td><?= $record->date ?></td>
                        <td><?= "Rp " . number_format($record->subtotal, 0, ",", ".") ?></td>
                          <td><?= "Rp " . number_format($record->other_cost, 0, ",", ".") ?></td>
                          <td><?= "Rp " . number_format($record->grand_total, 0, ",", ".") ?></td>
                        <td><span class="badge badge-<?= $color ?>"><?= $record->booking_status_name ?></span></td>
                        <td><?= "$record->bank_name - $record->account_number" ?></td>
                      </tr>
                      <?php $no++; } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->