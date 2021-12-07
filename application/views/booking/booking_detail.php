  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?= $page_name ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?= $this->uri->segment(1); ?></li>
            </ol>
          </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php if($message != null or $message != '') { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $message; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php } ?>
        <?php if($error != null or $error != '') { ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php } ?>
        <div class="row">
          <div class="col-12">
            <div class="callout callout-info">
              <row>
                <div class="col-12">
                  <h5><i class="fas fa-info"></i> Status pesanan: </h5>
                  <?= $record->booking_status_name ?> 
                </div>
              </row>
              <br/>
              <?php if($this->session->userdata('user_type') == 'admin'): ?>
              <row>
                <div class="col-12">
                  <?php if($record->next_booking_status != null && $record->booking_status != 'waiting_payment'): ?>
                    <button type="button" id="update-booking-status" class="btn btn-outline-success btn-sm">
                      <?= $record->next_booking_status_name ?>
                    </button>
                  <?php endif ?>
                  <?php if($record->booking_status == 'waiting_confirmation'): ?>
                    <button type="button" id="reject-booking" class="btn btn-outline-danger btn-sm">
                      Tolak
                    </button>
                  <?php endif ?>
                </div>
              </row>
              <?php endif ?>
              <?php if($this->session->userdata('user_type') == 'customer' && $record->booking_status == 'shipped'): ?>
                <button type="button" id="update-booking-status" class="btn btn-outline-success btn-sm">
                  <?= $record->next_booking_status_name ?>
                </button>
              <?php endif ?>
            </div>
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> Aurafoods Frozen
                    <small class="float-right">Tanggal: <?= $record->created_at ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Dari
                  <address>
                    <strong><?= 'Aurafoods Frozen '.$record->shop_name ?></strong><br>
                    <?= $record->shop_address.', '.$record->shop_postal_code ?><br>
                    Phone: <?= $record->shop_phone ?><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  Kepada
                  <address>
                    <strong><?= $record->user_name ?></strong><br>
                    <?= $record->address.', '.$record->postal_code ?><br>
                    Phone: <?= $record->phone ?><br>
                    Email: <?= $record->user_email ?>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Invoice ID <?= $record->id ?></b><br>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Nama produk</th>
                      <th>Harga</th>
                      <th>Qty</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($records as $booking_item) : ?>
                      <tr>
                        <td><?= $booking_item->name ?></td>
                        <td><?= 'Rp. '.number_format($booking_item->price, 0, ",", ".") ?></td>
                        <td><?= $booking_item->qty ?></td>
                        <td><?= 'Rp. '.number_format($booking_item->subtotal, 0, ",", ".") ?></td>
                      </tr>
                    <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Metode Pembayaran</p>

                  <p class="text well well-sm shadow-none" style="margin-top: 10px;">
                    <strong><?= "$record->bank_name - $record->account_number" ?></strong>
                  </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <div class="table-responsive">
                    <table class="table">
                      <?php if($record->other_cost != null) : ?>
                      <tr>
                        <th>Biaya Tambahan</th>
                        <td><?= 'Rp. '.number_format($record->other_cost, 0, ",", ".") ?></td>
                      </tr>
                      <?php endif ?>
                      <?php if($record->other_cost_note != null && $record->other_cost_note != '') : ?>
                      <tr>
                        <th>Note Biaya Tambahan</th>
                        <td><?= $record->other_cost_note ?></td>
                      </tr>
                      <?php endif ?>
                      <tr>
                        <th style="width:50%">Total:</th>
                        <td><?= 'Rp. '.number_format($subtotal, 0, ",", ".") ?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <?php if($record->booking_status != 'canceled') { ?>
                  <a href="<?= base_url("booking?id=$record->id&print=true") ?>" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Cetak</a>
                  <?php } ?>
                  <?php if($record->booking_status == 'waiting_payment' && $this->session->userdata('user_type') == 'customer') { ?>
                  <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#modal-submit-payment"><i class="far fa-credit-card"></i> Upload Bukti Bayar
                  </button>
                  <?php } ?>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>
      <!-- /.container-fluid -->
      <div class="modal fade" id="modal-submit-payment">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Upload Bukti Bayar</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="<?php echo base_url("booking/uploadpaymentreceipt") ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="file" name="image" class="form-control" value="" required>
                      <input type="hidden" name="id" class="form-control" value="<?= $record->id ?>" required>
                    </div>
                  </div>
                </div>
                <button class="btn btn-primary btn-sm" type="submit">Upload</button>
              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <div class="modal fade" id="modal-confirmed">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Konfirmasi pesanan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Pilih Toko</label>
                    <select name="shop_id" id="shop_id" class="form-control select2bs4" style="width: 100%;" required>
                      <?php 
                      foreach ($shops as $shop) {
                        echo '<option value="'.$shop->id.'">'.$shop->name.' - '.$shop->area_name.'</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              <button class="btn btn-primary btn-sm" id="confirm-booking-status">Konfirmasi</button>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <div class="modal fade" id="modal-shipping">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Kirim pesanan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Nomer Resi (Opsional)</label>
                    <input type="text" name="awb_number" id="awb_number" class="form-control">
                  </div>
                </div>
              </div>
              <button class="btn btn-primary btn-sm" id="shipped-booking-status">Kirim</button>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <div class="modal fade" id="modal-checking-payment">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Cek pembayaran</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="img-square-wrapper">
                      <img src="<?= ($record->payment_url != null) ? base_url("$record->payment_url") : base_url("assets/images/image_placeholder.png") ?>" class="card-img-top" style="object-fit: contain;" alt="<?= base_url("$record->payment_url") ?>">
                    </div>
                  </div>
                </div>
              </div>
              <button class="btn btn-primary btn-sm" id="confirm-payment">Konfirmasi pembayaran</button>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <div class="modal fade" id="modal-other-cost">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Biaya Tambahan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Biaya Tambahan</label>
                    <input type="number" name="other_cost" id="other_cost" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Catatan Biaya Tambahan</label>
                    <textarea class="form-control" name="other_cost_note" id="other_cost_note" placeholder="Contoh: Biaya pengiriman"></textarea>
                  </div>
                </div>
              </div>
              <button class="btn btn-primary btn-sm" id="send-bill">Konfirmasi</button>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    function updateBookingStatus(data) {
      $.LoadingOverlay("show");

      <?php
      $user_type = $this->session->userdata('user_type');
      if ($user_type == 'customer') {
        $url = 'booking/customerupdatebookingstatus';
      } else if ($user_type =='admin') {
        $url = 'booking/adminupdatebookingstatus';
      }
      ?>

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url($url) ?>",
        data: data,
        dataType: "json",
        success: function(resultData) { 
          $.LoadingOverlay("hide");

          if (resultData.error != null) {
            Swal.fire({
              toast: true,
              icon: 'error',
              showCloseButton: true,
              position: 'center-top',
              showConfirmButton: false,
              title: '&nbsp;&nbsp;&nbsp;Error!',
              text: resultData.error,
              timer: 5000
            });
          } else {
            location.reload();                
          }

        }
      });
    }

    $(document).ready(function() {
      $( "#shipped-booking-status" ).click(function() {
        var data = {
          'id':<?= $record->id ?>,
          'booking_status': 'shipped',
          'shop_id': 1,
          'awb_number': $("#awb_number").val()
        }

        updateBookingStatus(data);
      });

      $( "#confirm-booking-status" ).click(function() {
        var data = {
          'id':<?= $record->id ?>,
          'booking_status': 'confirmed',
          'shop_id': $("#shop_id").val()
        }

        updateBookingStatus(data);
      });

      $( "#send-bill" ).click(function() {
        var data = {
          'id':<?= $record->id ?>,
          'booking_status': 'waiting_payment',
          'other_cost': $("#other_cost").val(),
          'other_cost_note': $("#other_cost_note").val()
        }

        updateBookingStatus(data);
      });

      $( "#confirm-payment" ).click(function() {
        var bookingStatus = 'process';

        var data = {
          'id':<?= $record->id ?>,
          'booking_status': bookingStatus
        }

        updateBookingStatus(data);
      });

      $( "#update-booking-status" ).click(function() {
        var bookingStatus = "<?= $record->next_booking_status ?>";

        var data = {
          'id':<?= $record->id ?>,
          'booking_status': bookingStatus
        }

        if (bookingStatus == 'confirmed') {
          updateBookingStatus(data);
        } else if (bookingStatus == 'shipped') {
          $('#modal-shipping').modal('show');
        } else if (bookingStatus == 'waiting_payment') {
          $('#modal-other-cost').modal('show');
        } else {
          if ("<?= $record->booking_status ?>" == "checking_payment") {
            $('#modal-checking-payment').modal('show'); 
          } else {
            updateBookingStatus(data);
          }
        }
        
      });

      $( "#reject-booking" ).click(function() {
        if (confirm('Apakah anda yakin menolak pesanan ini?')) {
          var data = {
            'id':<?= $record->id ?>,
            'booking_status': 'canceled'
          }

          updateBookingStatus(data);
        }
      });

    });
  </script>