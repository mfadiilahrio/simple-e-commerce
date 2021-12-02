  <?php
  $action = ($record != null) ? "Ubah" : "Tambah";
  $id = (isset($record->id)) ? $record->id : '';
  $category_id = (isset($record->category_id)) ? $record->category_id : '';
  $brand_id = (isset($record->brand_id)) ? $record->brand_id : '';
  $brand_name = (isset($record->brand_name)) ? $record->brand_name : '';
  $category_name = (isset($record->category_name)) ? $record->category_name : '';
  $name = (isset($record->name)) ? $record->name : '';
  $description = (isset($record->description)) ? $record->description : '';
  $price = (isset($record->price)) ? $record->price : '';
  $qty = (isset($record->qty)) ? $record->qty : '';
  $image_url = (isset($record->image_url)) ? $record->image_url : '';
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
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
              <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="<?= base_url("product/update") ?>" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group pb-3">
                    <div class="img-square-wrapper">
                      <img src="<?= ($image_url != null) ? base_url("$image_url") : base_url("assets/images/image_placeholder.png") ?>" class="card-img-top" style="width: 100%;object-fit: contain;max-width: 300px" alt="<?= base_url("$image_url") ?>">
                    </div>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                  <div class="form-group">
                    <h5 class="text text-bold"><?= $name; ?></h5>
                    <?php if($qty < 1) : ?> 
                      <p class="text text-danger text-sm">Stok Habis</p> 
                    <?php endif ?>
                  </div>
                  <div class="form-group">
                    <h5 class="text text-bold"><?= "Rp " . number_format($price, 0, ",", ".") ?></h5>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-2">
                        <label>Merek :</label>
                      </div>
                      <div class="col-md-10">
                        <p><?= $brand_name ?></p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-2">
                        <label>Kategori :</label>
                      </div>
                      <div class="col-md-10">
                        <p><?= $category_name ?></p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 pt-3">
                        <div onclick="addToCartItems(<?= $id ?>)" class="btn btn-sm btn-outline-info btn-block">Tambah ke keranjang</div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Deskripsi</label>
                    <p><?= $description ?></p>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </form>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    function addToCartItems(id) {
      var data = {
        'user_id' : <?= $this->session->userdata('user_id') ?>,
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
            $('#cart_total').text(resultData.result.qty);

            Swal.fire({
              toast: true,
              icon: 'success',
              showCloseButton: true,
              position: 'top',
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