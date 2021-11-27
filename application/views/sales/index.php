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
        <?php if($error != null or $error != '') { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $error; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php } ?>
        <!-- card -->
        <div class="callout callout-success">
          <h6><i class="fas fa-info"></i> Total seluruh penjualan:</h6>
          <h1 class="text text-success"><strong><?= "Rp " . number_format($total_all_sales->total, 0, ",", ".") ?></strong></h1>
        </div>

        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Filter</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo base_url("sales") ?>" method="POST">
                  <div class="row">
                    <div class="form-group col-md-3">
                      <label>Pilih bulan</label>
                      <input type="text" name="month" class="form-control month" readonly value="<?php echo $month; ?>">
                    </div>
                    <div class="form-group col-md-3">
                      <label>Pilih tahun</label>
                      <input type="text" name="year" class="form-control year" readonly value="<?php echo $year ?>">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Filter</button>
                </form>
              </div>
              <!-- /.col -->
              <div class="col-md-6">
              </div>
              <!-- /.col -->
            </div>
            <div class="row mt-5">
              <div class="col-md-12">
                <?php if($total_sales != null) : ?>
                  <div class="callout callout-info">
                    <h6>Total penjualan:</h6>
                    <h3 class="text text-info"><strong><?= "Rp " . number_format($total_sales->total, 0, ",", ".") ?></strong></h3>

                    <a href="<?= base_url("sales/printsales?month=$month&year=$year") ?>" rel="noopener" target="_blank">
                      <button class="btn btn-default"><i class="fas fa-print"></i> Cetak</button>
                    </a>
                  </div>
                <?php endif ?>
                <br/>
                <?php if($records != null) : ?>
                  <table id="example1" class="table table-bordered table-hover nowrap">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Tipe</th>
                        <th>Customer</th>
                        <th>Area</th>
                        <th>Keluhan</th>
                        <th>Tanggal</th>
                        <th>Biaya Tambahan</th>
                        <th>Total</th>
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
                        <tr>
                          <td><?= $no; ?></td>
                          <td><?= $record->id ?></td>
                          <td><span class="badge badge-<?= ($record->type == 'booking') ? 'success' : 'secondary' ?>"><?= ($record->type == 'booking') ? 'Servis' : 'Belanja' ?></span></td>
                          <td><?= $record->user_name ?></td>
                          <td><?= $record->area_name ?></td>
                          <td class="text-truncate" style="max-width: 150px;"><?= $record->complaint ?></td>
                          <td><?= $record->date ?></td>
                          <td><?= "Rp " . number_format($record->other_cost, 0, ",", ".") ?></td>
                          <td><?= "Rp " . number_format($record->total, 0, ",", ".") ?></td>
                          <td><span class="badge badge-<?= $color ?>"><?= $record->booking_status_name ?></span></td>
                          <td><?= "$record->bank_name - $record->account_number" ?></td>
                        </tr>
                        <?php $no++; } ?>
                      </tbody>
                    </table>
                  <?php endif ?>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('.month').datepicker({
        format :'mm',
        startView: 1,
        minViewMode: 1,
        maxViewMode: 1,
        autoclose: true
      })
      $('.year').datepicker({
        format :'yyyy',
        startView: 2,
        minViewMode: 2,
        maxViewMode: 2,
        autoclose: true
      })      
    });
  </script>