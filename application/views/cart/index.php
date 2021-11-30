  <?php
    if ($this->session->userdata("address") != '' && $this->session->userdata("address") != null) {
      $is_empty_address = false;
    } else {
      $is_empty_address = true;
    }
  ?>
  <style type="text/css">
    .qty:hover {
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
            <h1><?= ($this->input->get('type') == 'shopping') ? $page_name.' Belanja' : $page_name.' Servis' ?></h1>
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
        <div class="row" id="cart-content" <?= ($hide_content) ? 'hidden' : '' ?>>
          <div class="col-sm-9">
            <!-- card -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><?= 'Daftar '.$page_name; ?></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <?php foreach ($records as $record) : ?>
                    <div class="col-md-6" id="item-<?= $record->id ?>">
                      <div class="card">
                        <div class="card-horizontal" style="display: flex;flex: 1 1 auto;">
                          <div class="img-square-wrapper">
                            <img src="<?= ($record->image_url != null) ? base_url("$record->image_url") : base_url("assets/images/image_placeholder.png") ?>" class="card-img-top" style="height: 120px; width: 100px;object-fit: contain;" alt="<?= base_url("$record->image_url") ?>">
                          </div>
                          <div class="card-body">
                            <p class="text text-bold"><?= $record->name ?></p>
                            <p class="text text-secondary"><?= $record->brand_name ?></p>
                            <p class="text text-secondary"><?= "Rp " . number_format($record->price, 0, ",", ".") ?></p>
                            <div class="form-group" style="width:100px">
                              <div class="input-group input-group-sm">
                                <div class="input-group-prepend" onclick="updateCartItem(<?= $record->id ?>, 'decrease')">
                                  <button type="button" class="btn btn-outline-danger"><i class="fa fa-minus"></i></button>
                                </div>
                                <input type="text" id="qty-<?= $record->id ?>" class="form-control input-number" value="<?= $record->qty ?>" min="1" max="100" readonly/>
                                <div class="input-group-append" onclick="updateCartItem(<?= $record->id ?>, 'increase')">
                                  <button type="button" class="btn btn-outline-info"><i class="fa fa-plus"></i></button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="card-footer">
                          <button type="button" class="btn btn-outline-secondary btn-sm float-right" onclick="deleteCartItem(<?= $record->id ?>, 'decrease')"><i class="fa fa-trash"></i></button>
                        </div>
                      </div>
                    </div>
                  <?php endforeach ?>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-3">
            <div class="card card-default">
              <div class="card-header">
                <h5 class="card-title">Detail</h5>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-4">
                      <p class="text text-default">Subtotal :</p>
                    </div>
                    <div class="col-md-8">
                      <p class="text text-bold float-right" id="subtotal"><?= "Rp " . $subtotal ?></p>
                    </div>
                  </div>
                  <?php if($booking_id != null && $user_id != null): ?>
                  <form action="booking/addtobookingitems" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
                    <input type="hidden" name="user_id" value="<?= $user_id ?>">
                    <button class="btn btn-outline-primary btn-block">Tambahkan Barang</button>
                  </form>
                  <?php endif ?>
                </div>
                <?php if($booking_id == null): ?>
                <form action="booking/createbooking" method="POST" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Area</label>
                        <select name="area_id" class="form-control select2bs4" style="width: 100%;" required>
                          <option>--Pilih Area--</option>
                          <?php 
                          foreach ($areas as $data) {
                            echo '<option value="'.$data->id.'">'.$data->name.'</option>';
                          }
                          ?>
                        </select>
                        <input type="hidden" name="type" value="<?= $this->input->get('type') ?>">
                      </div>
                      <?php if ($this->input->get('type') == 'booking') : ?>
                      <div class="form-group">
                        <label>Keluhan</label>
                        <textarea name="complaint" class="form-control" required></textarea>
                      </div>
                      <div class="form-group">
                        <label>Tanggal</label>
                        <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" name="date" data-target="#reservationdatetime" readonly />
                          <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                        </div>
                      </div>
                      <?php endif ?>
                      <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control" required><?= $this->session->userdata('address') ?></textarea>
                      </div>
                      <div class="form-group">
                        <label>Kode POS</label>
                        <input type="number" name="postal_code" class="form-control" value="<?= $this->session->userdata('postal_code') ?>" required>
                      </div>
                      <div class="form-group">
                        <label>Metode pembayaran</label>
                        <select name="bank_account_id" class="form-control select2bs4" style="width: 100%;" required>
                          <option>--Pilih Rekening--</option>
                          <?php 
                          foreach ($bank_accounts as $data) {
                            echo '<option value="'.$data->id.'">'.$data->method_name.'</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <!-- /.col -->
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <button type="submit" id="create-booking" class="btn btn-outline-primary btn-block" <?= (count($records) < 1) ? 'hidden' : '' ?>>Buat Pesanan</button>
                    </div>
                  </div>
                </form>
                <?php endif ?>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div class="row" id="empty-cart-content" <?= ($hide_content) ? '' : 'hidden' ?>>
          <div class="card col-md-12">
            <div class="card-body">
              <img src="<?= base_url('assets/images/empty_cart.png') ?>" class="img-fluid mx-auto d-block" alt="Empty Cart">
              <h1 class="text text-danger text-center mt-5">Keranjangmu kosong :(</h1>
            </div>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    function updateCartItem(id, type_update) {

      var urlParams = new URLSearchParams(window.location.search);

      var data = {
        'user_id':<?= $user_id ?>,
        'type': urlParams.get('type'),
        'id': id,
        'type_update': type_update
      }

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url("cart/updatecartitem")?>",
        data: data,
        dataType: "json",
        success: function(resultData) { 
          if (resultData.error != null) {
            window.alert(resultData.error);
          } else {
            $('#subtotal').text('Rp.' + resultData.result.subtotal);
            $('#qty-' + id).val(resultData.result.cartItem.qty);
            $('#cart_total').text(resultData.result.cartTotal.qty);
          }
        }
      });
    }

    function deleteCartItem(id) {
      var urlParams = new URLSearchParams(window.location.search);

      var data = {
        'type': urlParams.get('type'),
        'id': id,
        'user_id': <?= $user_id ?>
      }

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url("cart/deletecartitem")?>",
        data: data,
        dataType: "json",
        success: function(resultData) { 
          if (resultData.error != null) {
            window.alert(resultData.error);
          } else {
            if (resultData.result.subtotal == "0") {
              $('#create-booking').attr('hidden', true);
            }

            if (resultData.result.hideContent) {
              $('#cart-content').attr('hidden', true);
              $('#empty-cart-content').removeAttr('hidden');
            }

            $('#subtotal').text('Rp.' + resultData.result.subtotal);
            $('#item-' + id).remove();
            $('#booking_cart_total').text(resultData.result.bookingCartTotal.qty);
            $('#cart_total').text(resultData.result.cartTotal.qty);
          }
        }
      });
    }
  </script>