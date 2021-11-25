  <style type="text/css">
  #floating-cart{
    position:fixed;
    width:60px;
    height:60px;
    bottom:40px;
    right:40px;
    background-color:#17A2B8;
    color:#FFF;
    border-radius:50px;
    text-align:center;
    box-shadow: 2px 2px 3px #999;
  }

  #floating-cart:hover {
    cursor: pointer;
  }

  #booking-floating-cart{
    margin-top:22px;
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
        <?php if($error != null or $error != '') { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $error; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php } ?>
        <!-- card -->
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
            <?php if($record != null): ?>
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Info Pesanan</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <address>
                  Booking ID : <strong><?= $record->id ?></strong><br>
                </address>
                <address>
                  <?= $record->user_name ?><br>
                  <?= $record->address.', '.$record->postal_code ?><br>
                  Phone: <?= $record->phone ?><br>
                  Email: <?= $record->user_email ?>
                </address>
              </div>
            </div>
            <?php endif ?>
            <form action="booking/createbooking" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <?php if($record == null): ?>
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
                  <?php endif ?>
                  <div class="form-group">
                    <label>Merek</label>
                    <select name="brand_id" id="brand_id" class="form-control select2bs4" style="width: 100%;" required>
                      <option>--Pilih Merek--</option>
                      <?php 
                      foreach ($brands as $data) {
                        echo '<option value="'.$data->id.'">'.$data->name.'</option>';
                      }
                      ?>
                    </select>
                    <input type="hidden" name="type" value="booking" required>
                  </div>
                  <div class="form-group">
                    <label>Tipe</label>
                    <select name="brand_type_id" id="brand_type_id" class="form-control select2bs4" style="width: 100%;" required>
                    </select>
                  </div>
                  <?php if($record == null): ?>
                  <div class="form-group">
                    <label>Keluhan</label>
                    <textarea name="complaint" class="form-control" required></textarea>
                  </div>
                  <div class="form-group">
                    <label>Tanggal</label>
                    <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                      <input type="text" class="form-control datetimepicker-input" name="date" data-target="#reservationdatetime" readonly required />
                      <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="address" class="form-control" required><?= $this->session->userdata('address') ?></textarea>
                  </div>
                  <?php endif ?>
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                </div>
                <!-- /.col -->
              </div>
              <?php if($record == null): ?>
              <div class="row">
                <div class="col-md-3">
                  <button type="submit" id="create-booking" class="btn btn-outline-primary">Buat Pesanan</button>
                </div>
              </div>
              <?php endif ?>
            </div>
          </form>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <div class="row" id="products">
        </div>
      </div>
      <!-- /.container-fluid -->
      <?php $booking_cart_total = (int) (isset($booking_cart_total->qty)) ? $booking_cart_total->qty : 0; ?>
      <div id="floating-cart" <?= ($booking_cart_total > 0) ? "" : "hidden" ?>>
        <i class="fas fa-angle-right" id="booking-floating-cart"></i>
        <span class="badge badge-warning navbar-badge" id="floating_booking_cart_total"><?= $booking_cart_total ?></span>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#floating-cart').on( "click", function() {
        <?php if($record != null): ?>
          window.location.href = "<?= base_url('cart?type=booking&user_id='.$record->user_id.'&booking_id='.$record->id) ?>";
        <?php endif ?>
      });

      $('#brand_id').change(function(e) {

        $('#brand_type_id').empty();
        $('#products').empty();

        var data = {
          'brand_id':$('#brand_id').val()
        }

        $.LoadingOverlay("show");

        $.ajax({
          type: 'GET',
          url: "<?php echo base_url("bookingservice/getbrandtypebybrandid")?>",
          data: data,
          dataType: "json",
          success: function(resultData) { 
            $.LoadingOverlay("hide");

            var toAppend = '';
            toAppend += '<option>--Pilih Tipe</option>';
            $.each(resultData,function(i,o){
              toAppend += '<option value="'+o.id+'">'+o.name+'</option>';
           });
            $('#brand_type_id').append(toAppend);
          }
        });
      });

      $('#brand_type_id').change(function(e) {

        var id = <?= (isset($record->id)) ? $record->id : 0 ?>;

        if (id != 0) {
          $('#products').empty();

          var data = {
            'brand_type_id':$('#brand_type_id').val()
          }

          $.LoadingOverlay("show");

          $.ajax({
            type: 'GET',
            url: "<?php echo base_url("bookingservice/getitemsbybrandtypeid")?>",
            data: data,
            dataType: "json",
            success: function(resultData) { 
              $.LoadingOverlay("hide");

              var toAppend = '';
              $.each(resultData,function(i,o){
                var image_url = "";
                if (o.image_url.length > 0) {
                  image_url = "<?php echo base_url('"+o.image_url+"')?>";
                } else {
                  image_url = "<?php echo base_url("assets/images/image_placeholder.png")?>";
                }
                toAppend += '<div class="col-md-2">' +
                '<div class="card">' +
                '<a href="#" class="p-3">' +
                '<img src="'+image_url+'" class="card-img-top" style="height: 200px;object-fit: contain;" alt="'+image_url+'">' +
                '</a>' +
                '<div class="card-body">' +
                '<div class="row">' +
                '<div class="col-md-12">' +
                '<h5 class="card-title text-bold">'+o.name+'</h5>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="card-footer">' +
                '<p class="text text-secondary">Rp.'+o.price+'</p>' +
                '<div onclick="addToBookingItems('+o.id+')" class="btn btn-sm btn-outline-info btn-block">Tambah ke keranjang</div>' +
                '</div>' +
                '</div>' +
                '<br>' +
                '</div>';
              });
              $('#products').append(toAppend);
            }
          });
        }
      });       
    });

    function addToBookingItems(id) {
      var data = {
        'user_id':<?= (isset($record->user_id)) ? $record->user_id : $this->session->userdata('user_id'); ?>,
        'type':'booking',
        'item_id':id
      }

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url("cart/addtocart")?>",
        data: data,
        dataType: "json",
        success: function(resultData) { 
          if (resultData.error != null) {
            window.alert(resultData.error);
          } else {
            if (resultData.result.qty > 0) {
              $('#floating-cart').removeAttr('hidden');
            }
            $('#booking_cart_total').text(resultData.result.qty);
            $('#floating_booking_cart_total').text(resultData.result.qty);

            Swal.fire({
              toast: true,
              icon: 'success',
              showCloseButton: true,
              position: 'top-start',
              showConfirmButton: false,
              title: '&nbsp;&nbsp;&nbsp;Berhasil!',
              text: resultData.message,
              timer: 3000
            })
          }
        }
      });
    }
  </script>